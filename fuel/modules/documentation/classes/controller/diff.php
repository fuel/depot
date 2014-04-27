<?php
/**
 * Part of Fuel Depot.
 *
 * @package    FuelDepot
 * @version    1.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2012 Fuel Development Team
 * @link       http://depot.fuelphp.com
 */

namespace Documentation;

class Controller_Diff extends Controller_Pagebase
{
	/**
	 * Documentation page diff
	 */
	public function action_index($page = null)
	{
		// fetch the requested page
		$page === null or $page = Model_Page::find($page);

		// do we have a page?
		if ($page)
		{
			// validate the access
			if ( ! $this->checkaccess())
			{
				\Messages::redirect('documentation/page/'.$page->id);
			}

			// load the available versions of the docs for this page
			$docs = Model_Doc::query()->where('page_id', '=', $page->id)->order_by('created_at', 'DESC')->get();

			// did we find more then one?
			if ( ! $docs or count($docs) < 2)
			{
				// nope, inform the user there's nothing to diff
				\Messages::error('No page versions available to run a diff on!');

				// and return to the page
				\Messages::redirect('documentation/page/'.$page->id);
			}
		}
		else
		{
			// how did we get here without a valid page id?
			\Messages::redirect('documentation');
		}

		// do we have something posted?
		if (\Input::post('cancel'))
		{
			// cancel button used
			\Messages::redirect('documentation/page/'.reset($docs)->page_id);
		}

		elseif (\Input::post('delete') and \Auth::has_access('access.staff'))
		{
			if ($selected = \Input::post('selected', false))
			{
				// delete the page cache
				\Cache::delete('documentation.version_'.reset($docs)->page->version_id.'.page_'.reset($docs)->page->id);

				foreach ($selected as $doc)
				{
					if (isset($docs[$doc]))
					{
						$docs[$doc]->delete();
					}
				}

				// all selected are deleted
				\Messages::success('Selected page versions were succesfully deleted!');
			}
			else
			{
				// nothing selected to delete
				\Messages::warning('No page versions were selected!');
			}

			// and return to the diff page
			\Messages::redirect('documentation/diff/'.reset($docs)->page_id);
		}

		elseif (\Input::post('view'))
		{
			// do we have valid input?
			if (\Input::post('before') and \Input::post('after'))
			{
				if (\Input::post('before') == \Input::post('after'))
				{
					// nope, inform the user there's nothing to diff
					\Messages::error('No point comparing a version with itself!');

					// invalid input, try again
					\Messages::redirect('documentation/diff/'.reset($docs)->page_id);
				}
				else
				{
					$before = \Input::post('before');
					$after = \Input::post('after');
					if ($before < $after)
					{
						// swap them if needed
						$tmp = $before;
						$before = $after;
						$after = $tmp;
					}

					if (isset($docs[$before]) and isset($docs[$after]))
					{
						// load the diff class
						require_once APPPATH.'vendor'.DS.'finediff'.DS.'finediff.php';

						// add the view diff partial
						$details = \Theme::instance()->view('documentation/viewdiff');

						// run the diff on the two versions
						$opcodes = \FineDiff::getDiffOpcodes($docs[$after]->content, $docs[$before]->content);
						$details->set('diff', $this->renderpage(\FineDiff::renderDiffToMarkdownFromOpcodes($docs[$after]->content, $opcodes)), false);

						$details->set('before', $before);
						$details->set('after', $after);
					}
					else
					{
						// nope, inform the user there's nothing to diff
						\Messages::error('Invalid page versions selected to run a diff on!');

						// invalid input, try again
						\Messages::redirect('documentation/diff/'.reset($docs)->page_id);
					}
				}
			}
			else
			{
				// nope, inform the user there's nothing to diff
				\Messages::error('No page versions selected to run a diff on!');

				// invalid input, try again
				\Messages::redirect('documentation/diff/'.reset($docs)->page_id);
			}
		}

		else
		{
			// add the view diff partial
			$details = \Theme::instance()->view('documentation/diffindex');

			// pass the docs to it
			$details->set('docs', $docs);
		}

		// create the page partial
		$partial = $this->buildpage($page)->set('details', $details);
	}

}
