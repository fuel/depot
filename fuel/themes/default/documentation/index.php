<?php \Theme::instance()->asset->css(array('docs.css'), array(), 'header'); ?>

<div style="border-bottom:1px solid #ddd;padding-bottom:10px;">
	<div style="float:left;">
		<h1>Online Documentation</h1>
	</div>
	<div style="float:right">
		<form style="text-align:right;" name="version_select" method="POST">
			<h5 style="margin-bottom:0px;">FuelPHP version: </h5>
			<?php echo \Form::select('branch', $selection['version'], $versions, array('style' => 'min-width:125px;', 'onchange' => 'this.form.action = this.form.action + \'/api/version/\' + this.value; this.form.submit();')); ?>
		</form>
	</div>
	<div class="clearfix"></div>
</div>

<div id="docs">
	<div style="float:left;width:250px;padding-top:10px;margin-right:0px;overflow:hidden;">
			<h5>The Framework</h5>

<ul class="menutree">
	<li><a class="collapsed" href="#">Introduction</a>
		<ul>
			<li><a href="#">Welcome !</a></li>
			<li><a href="#">Requirements</a></li>
			<li><a href="#">License</a></li>
		</ul>
	</li>
	<li><a class="collapsed" href="#">Installation</a>
		<ul>
			<li><a href="#">Using the commandline</a></li>
			<li><a href="#">Downloads</a></li>
			<li><a href="#">Manual installation</a></li>
			<li><a href="#">Alternative setups</a></li>
			<li><a href="#">Troubleshooting</a></li>
		</ul>
	</li>
	<li><a class="collapsed" href="#">Architecture</a>
		<ul>
			<li><a href="#">Module-View-Controller (MVC)</a></li>
			<li><a href="#">HMVC requests</a></li>
			<li><a href="#">Environments</a></li>
			<li><a href="#">Configuration</a></li>
			<li><a href="#">Security</a></li>
			<li><a href="#">Routing</a></li>
			<li><a href="#">Modules</a></li>
			<li><a href="#">Packages</a></li>
			<li><a href="#">Error handling</a></li>
		</ul>
	</li>
	<li><a class="collapsed" href="#">Components</a>
		<ul>
			<li><a href="#">Constants</a></li>
			<li><a class="collapsed" href="#">Classes</a>
				<ul>
					<li><a href="#">Introduction</a></li>
					<li><a href="#">Extending the core</a></li>
				</ul>
			</li>
			<li><a class="collapsed" href="#">Controllers</a>
				<ul>
					<li><a href="#">Base controllers</a></li>
					<li><a href="#">Template controller</a></li>
					<li><a href="#">Rest controller</a></li>
				</ul>
			</li>

			<li><a href="#">Models</a></li>
			<li><a href="#">Viewmodels</a></li>
			<li><a href="#">Views</a></li>
			<li><a href="#">Migrations</a></li>
			<li><a href="#">Tasks</a></li>
		</ul>
	</li>
	<li><a class="collapsed" href="#">Contribute</a>
		<ul>
			<li><a href="#">How to contribute</a></li>
			<li><a href="#">Coding standards</a></li>
			<li><a href="#">Credits</a></li>
		</ul>
	</li>
</ul>
			<h5>The Reference</h5>

<ul class="menutree">
	<li><a class="collapsed" href="#">Core classes</a>
		<ul>
			<li><a class="collapsed" href="#">Agent</a>
				<ul>
					<li><a href="#">Configuration</a></li>
					<li><a href="#">Usage</a></li>
				</ul>
			</li>
			<li><a href="#">Arr</a></li>
			<li><a class="collapsed" href="#">Asset</a>
				<ul>
					<li><a href="#">Configuration</a></li>
					<li><a href="#">Basic usage</a></li>
					<li><a href="#">Advanced usage</a></li>
				</ul>
			</li>
			<li><a href="#">Autoloader</a></li>
			<li><a class="collapsed" href="#">Cache</a>
				<ul>
					<li><a href="#">Configuration</a></li>
					<li><a href="#">Basic usage</a></li>
					<li><a href="#">Advanced usage</a></li>
				</ul>
			</li>
			<li><a href="#">Cli</a></li>
			<li><a href="#">Config</a></li>
			<li><a href="#">Cookie</a></li>
			<li><a href="#">Crypt</a></li>
			<li><a class="collapsed" href="#">Database</a>
				<ul>
					<li><a href="#">Introduction</a></li>
					<li><a href="#">Basic usage</a></li>
					<li><a href="#">DB</a></li>
					<li><a href="#">DBUtil</a></li>
					<li><a href="#">Query builder</a></li>
				</ul>
			</li>
			<li><a href="#">Date</a></li>
			<li><a href="#">Debug</a></li>
			<li><a href="#">Event</a></li>
			<li><a class="collapsed" href="#">Fieldsets</a>
				<ul>
					<li><a href="#">Introduction</a></li>
					<li><a href="#">Basic usage</a></li>
					<li><a href="#">Advanced usage</a></li>
					<li><a href="#">Fieldset</a></li>
					<li><a href="#">Field</a></li>
				</ul>
			</li>
			<li><a class="collapsed" href="#">File</a>
				<ul>
					<li><a href="#">Introduction</a></li>
					<li><a href="#">Basic usage</a></li>
					<li><a href="#">Advanced usage</a></li>
					<li><a href="#">Handlers</a></li>
				</ul>
			</li>
			<li><a href="#">Finder</a></li>
			<li><a href="#">Form</a></li>
			<li><a href="#">Format</a></li>
			<li><a href="#">Ftp</a></li>
			<li><a href="#">Fuel</a></li>
			<li><a href="#">Html</a></li>
			<li><a href="#">Image</a></li>
			<li><a href="#">Inflector</a></li>
			<li><a href="#">Input</a></li>
			<li><a href="#">Lang</a></li>
			<li><a href="#">Log</a></li>
			<li><a href="#">Markdown</a></li>
			<li><a href="#">Migrate</a></li>
			<li><a class="collapsed" href="#">Model_Crud</a>
				<ul>
					<li><a href="#">Introduction</a></li>
					<li><a href="#">Methods</a></li>
				</ul>
			</li>
			<li><a class="collapsed" href="#">Mongo_Db</a>
				<ul>
					<li><a href="#">Introduction</a></li>
					<li><a href="#">Methods</a></li>
				</ul>
			</li>
			<li><a href="#">Num</a></li>
			<li><a href="#">Package</a></li>
			<li><a href="#">Pagination</a></li>
			<li><a href="#">Profiler</a></li>
			<li><a href="#">Redis</a></li>
			<li><a href="#">Request</a></li>
			<li><a href="#">Response</a></li>
			<li><a href="#">Router</a></li>
			<li><a href="#">Security</a></li>
			<li><a class="collapsed" href="#">Session</a>
				<ul>
					<li><a href="#">Configuration</a></li>
					<li><a href="#">Basic usage</a></li>
					<li><a href="#">Advanced usage</a></li>
				</ul>
			</li>
			<li><a href="#">Str</a></li>
			<li><a class="collapsed" href="#">Theme</a>
				<ul>
					<li><a href="#">Introduction</a></li>
					<li><a href="#">Methods</a></li>
					<li><a href="#">Advanced usage</a></li>
				</ul>
			</li>
			<li><a class="collapsed" href="#">Upload</a>
				<ul>
					<li><a href="#">Introduction</a></li>
					<li><a href="#">Methods</a></li>
				</ul>
			</li>
			<li><a href="#">Uri</a></li>
			<li><a class="collapsed" href="#">Validation</a>
				<ul>
					<li><a href="#">Introduction</a></li>
					<li><a href="#">Methods</a></li>
					<li><a href="#">Errors</a></li>
				</ul>
			</li>
			<li><a href="#">View</a></li>
		</ul>
	</li>
	<li><a class="collapsed" href="#">Oil package</a>
		<ul>
			<li><a href="#">Introduction</a></li>
			<li><a href="#">Generate</a></li>
			<li><a href="#">Refine</a></li>
			<li><a href="#">Package</a></li>
			<li><a href="#">Console</a></li>
		</ul>
	</li>
	<li><a class="collapsed" href="#">Auth package</a>
		<ul>
			<li><a href="#">Introduction</a></li>
			<li><a href="#">Usage</a></li>
			<li><a class="collapsed" href="#">Drivers</a>
				<ul>
					<li><a href="#">Login</a></li>
					<li><a href="#">Groups</a></li>
					<li><a href="#">ACL</a></li>
				</ul>
			</li>
			<li><a class="collapsed" href="#">SimpleAuth</a>
				<ul>
					<li><a href="#">Introduction</a></li>
					<li><a href="#">Login</a></li>
					<li><a href="#">Groups</a></li>
					<li><a href="#">ACL</a></li>
				</ul>
			</li>
			<li><a href="#">Writing drivers</a></li>
		</ul>
	</li>
	<li><a class="collapsed" href="#">Email package</a>
		<ul>
			<li><a href="#">Introduction</a></li>
			<li><a href="#">Usage</a></li>
			<li><a href="#">Methods</a></li>
		</ul>
	</li>
	<li><a class="collapsed" href="#">ORM package</a>
		<ul>
			<li><a href="#">Introduction</a></li>
			<li><a href="#">Creating models</a></li>
			<li><a href="#">CRUD operations</a></li>
			<li><a class="collapsed" href="#">Relating models</a>
				<ul>
					<li><a href="#">Introduction</a></li>
					<li><a href="#">Belongs to</a></li>
					<li><a href="#">Has one</a></li>
					<li><a href="#">Has many</a></li>
					<li><a href="#">Many to many</a></li>
				</ul>
			</li>
			<li><a class="collapsed" href="#">Observers</a>
				<ul>
					<li><a href="#">Introduction</a></li>
					<li><a href="#">Included observers</a></li>
					<li><a href="#">Writing your own</a></li>
				</ul>
			</li>
		</ul>
	</li>
	<li><a class="collapsed" href="#">Parser package</a>
		<ul>
			<li><a href="#">Introduction</a></li>
		</ul>
	</li>
</ul>

	</div>
	<div style="float:right;width:739px;padding-top:10px;margin-left:0px;padding-left:10px;">
		<?php echo $details; ?>
	</div>
	<div class="clearfix"></div>
</div>
