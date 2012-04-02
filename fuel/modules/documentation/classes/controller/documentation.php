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

class Controller_Documentation extends \Controller_Base_Public
{
	/**
	 * @var	array	parameters
	 */
	 protected $params = array();

	/**
	 * The module index
	 *
	 * @access  public
	 * @return  Response
	 */
	public function router($method, $params)
	{
		// make sure our params have a value
		empty($params) or $params = \Arr::to_assoc($params);

		empty($params['version']) and $params['version'] = 0;

		// store them
		$this->params = $params;

		// load the defined FuelPHP versions, ordered by version
		$result = \DB::select()->from('versions')->order_by('major', 'ASC')->order_by('minor', 'ASC')->order_by('branch', 'ASC')->execute();

		// create the dropdown array
		$dropdown = array();
		foreach ($result as $record)
		{
			$dropdown[$record['id']] = $record['major'].'.'.$record['minor'].'/'.$record['branch'];
		}

		// find the selected version by id match
		foreach ($result as $record)
		{
			if ($record['id'] == $this->params['version'])
			{
				$this->version = $record;
				break;
			}
		}

		// if not found, get the default one
		if (empty($this->version))
		{
			foreach ($result as $record)
			{
				if ($record['default'] == 1)
				{
					$this->version = $record;
					break;
				}
			}
		}

		// if not found, get the last one
		if (empty($this->version) and count($result) > 0 and ! empty($record))
		{
			$this->version = $record;
			$this->params['version'] = $this->version['id'];
		}

		// still if not found, give up!
		if (empty($this->version))
		{
			\Theme::instance()->set_partial('content', 'documentation/error');
		}
		else
		{
			// if no version was selected using the dropdown, select the default
			$this->params['version'] == 0 and \Response::redirect('documentation/version/'.$this->version['id']);

			// add the partial to the template
			\Theme::instance()->set_partial('content', 'documentation/index')->set(array('versions' => $dropdown, 'selection' => $this->params));

			// render the docs of the selected version
			$this->process();
		}
	}

	protected function process()
	{
$details = <<<LORUM
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ac lobortis sapien. Nulla pharetra eleifend odio, eget feugiat augue imperdiet at. Nulla facilisi. Sed adipiscing ullamcorper posuere. Cras tincidunt gravida libero, at gravida neque varius quis. Mauris nunc nisi, venenatis vel sagittis id, facilisis at diam. Vivamus porta lectus ac diam eleifend auctor. Integer auctor est eget leo ultrices malesuada. Aliquam sollicitudin consequat massa, a congue velit luctus ut.</p>
<p>In hac habitasse platea dictumst. Fusce ac dolor lectus. Donec pretium, lacus vel congue luctus, massa mi gravida purus, et commodo nulla dui sed leo. Aliquam erat volutpat. Morbi vestibulum pellentesque velit non consequat. Praesent lacinia lacus eget leo imperdiet ullamcorper. Pellentesque sed nulla vel eros mattis tempus nec vitae enim. Sed non ante quis augue mollis suscipit vel sit amet metus. Vivamus tempor, enim dictum elementum scelerisque, sem libero imperdiet tortor, eget placerat justo lacus eu nisi. Nam sagittis turpis eget nisl varius a faucibus arcu imperdiet.</p>
<p>Integer enim urna, pulvinar ac posuere ut, bibendum eget tortor. Suspendisse potenti. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Etiam mauris magna, ullamcorper eget facilisis ut, mattis nec magna. Vivamus sit amet lorem arcu, non aliquam felis. Morbi consequat convallis semper. Pellentesque in velit sit amet massa viverra ornare vel in diam. Cras vehicula varius molestie. Nunc condimentum viverra lectus, eu mattis quam iaculis vel. Aenean mattis orci sed dui pellentesque dignissim. Curabitur ut sapien velit. Aenean at pellentesque ipsum. Maecenas ultricies libero sit amet felis semper auctor. Suspendisse est tortor, mattis vel condimentum in, aliquet et elit. Ut quis elit odio. Sed tincidunt egestas justo et semper.</p>
<p>In lobortis lacinia felis quis interdum. Donec erat ante, tristique fringilla laoreet non, varius at est. Proin ultricies pretium mattis. Maecenas ut nisl tellus, non commodo mauris. Donec venenatis hendrerit massa. Nullam turpis velit, rutrum viverra blandit et, interdum sit amet mi. Sed laoreet dolor sit amet dolor blandit rhoncus. In auctor sem sit amet leo eleifend id vulputate nunc dictum. Ut tempor pharetra nisi, ac auctor purus condimentum sodales. Nulla aliquam commodo tempor. Aenean viverra facilisis enim, sit amet iaculis sem volutpat at. Suspendisse gravida magna ut leo aliquet porttitor sagittis felis mattis. Nulla facilisi. Etiam a dui fringilla erat tempus aliquam. Proin vel erat feugiat nisl luctus vehicula sit amet in mauris.</p>
<p>Fusce porta blandit molestie. Nulla consequat ullamcorper ipsum sit amet molestie. Etiam vel tortor in diam condimentum dictum. Aliquam at erat eu augue egestas rhoncus eu vitae metus. Quisque lectus arcu, elementum quis lacinia ac, auctor sit amet ipsum. Donec lacus risus, sagittis vitae bibendum nec, vestibulum eget lectus. Duis non nulla eu turpis tempus placerat. Fusce a dolor nec justo vulputate vestibulum id sit amet elit. Fusce vehicula odio vitae erat molestie ultricies. Etiam lacinia venenatis massa id consequat. Aenean in pellentesque diam. Morbi id metus est, eget faucibus urna. Maecenas pharetra, sem vitae imperdiet bibendum, magna leo commodo mi, quis venenatis libero arcu non diam. Curabitur congue arcu a justo tempus placerat. Integer neque lacus, malesuada at consectetur sed, bibendum vitae ipsum. Nullam viverra pulvinar eros at rutrum.</p>
LORUM;

		// if no api details were selected, show the intro page
		rand(0,0) and $details = \Theme::instance()->view('documentation/intro');


		// set the content partial, add the details to it
		\Theme::instance()->get_partial('content', 'documentation/index')->set('details', $details, false);
	}
}
