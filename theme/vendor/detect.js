/**
 * Detect - Get data from the browser, format it, profit
 *
 * @since  1.0.0
 * @type {Object}
 */
var Detect = function(options){
	//set options to either a default or the chosen value
	options = options || {};
	options.format = options.format || false;
	options.addClasses = options.addClasses || true;
	options.classPrefix = options.classPrefix || 'default';
	options.installPluginUtility = options.installPluginUtility || true;
	options.installTests = options.installTests || false;
	options.noPlugins = options.noPlugins || false;
	options.noOS = options.noOS || false;
	options.noBrowser = options.noBrowser || false;
	
	var retVal = this.do(options);

	//add plugin utility to the global scope
	if(options.installPluginUtility)
		window.DJS_PluginUtility = new this.DJS_PluginUtility(this, retVal);

	if(options.installTests)
		window.DJS_Tests = new this.DJS_Tests(this, retVal);

	return retVal;
}

Detect.prototype = {
	/**
	 * Static version number
	 * 
	 * @since 1.0.0
	 * @type {Object}
	 */
	_Version: '1.1.0',

	/**
	 * Reference the window.navigator object so we can add/remove things if necessary
	 *
	 * @since  1.0.0
	 * @type {Object}
	 */
	_Navigator: window.navigator,

	/**
	 * Object that contains commonly required strings from the UA
	 * NOTE: accessing directly will give you incorrect data, use Detect.browser() 
	 * to access this data instead
	 * 
	 * @since 1.0.0
	 * @type {Object}
	 */
	_Browsers: {
		UNKNOWN: {name: 'unknown', engine: 'unknown', version: '0.0.0'},
		CHROME_WEBKIT: {name: 'chrome', engine: 'webkit', version: '0.0.0'},
		CHROME_BLINK: {name: 'chrome', engine: 'blink', version: '0.0.0'},
		FIREFOX: {name: 'firefox', engine: 'gecko', version: '0.0.0'},
		OPERA_PRESTO: {name: 'opera', engine: 'presto', version: '0.0.0'}, //preliminary support
		OPERA_BLINK: {name: 'opera', engine: 'blink', version: '0.0.0'}, //currently not tested for
		SAFARI: {name: 'safari', engine: 'webkit', version: '0.0.0'},
		IE: {name: 'ie', engine: 'trident', version: '0.0.0'},
	},

	/**
	 * User's operating system text placeholders
	 *
	 * @since  1.0.0
	 * @type {Object}
	 */
	_OS: {
		UNKNOWN: {name: 'Unknown', architecture: '32'},
		MAC: {name: 'Mac', architecture: '32'},
		WINDOWS: {name: 'Windows', architecture: '32'},
		LINUX: {name: 'Linux', architecture: '32'},
	},

	/**
	 * All the functions that return a useful value run a regex statement, so
	 * lets run it globally once instead of 3 times
	 *
	 * @since  1.0.0
	 * @type {array}
	 */
	_navParts: [],

	/**
	 * Sets the _Browser and _OS variables
	 *
	 * @param {object} options
	 * @since  1.0.0
	 * @return {object} [Aggregated data from this.browser, this.os and this.plugins]
	 */
	do: function(options){
		this._navParts = this._Navigator.userAgent.split(/\s*[;)(]\s*/);

		var output = {
			os: this.os(),
			browser: this.browser(),
			plugins: this.plugins(options),
		};

		if(options.noPlugins)
			delete output.plugins;

		if(options.noOS)
			delete output.os;

		if(options.noBrowser)
			delete output.browser;

		if(options.addClasses)
			this._addClasses(options, output);
		
		return output;
	},

	/**
	 * Determine the user's browser
	 *
	 * @param {object} options [Any required settings]
	 * @since  1.0.0
	 * @return {object}
	 */
	browser: function(options){
		var output = this._Browsers.UNKNOWN,
			os = this.os().system;

		//safari/webkit
		if(/^Version/.test(this._navParts[5]) && /Safari/.test(this._navParts[5])){
			switch(os){
				case this._OS.MAC.system:
					this._Browsers.SAFARI.version = this._navParts[5].split(' ')[0].substring(8); break;

				default:
					this._Browsers.SAFARI.version = this._navParts[5].split(' ')[0].substring(8);

			}

			output = this._Browsers.SAFARI;
		}

		//chrome/webkit
		if(/^Chrome/.test(this._navParts[5]) && /^AppleWebKit/.test(this._navParts[3])){
			switch(os){
				case this._OS.MAC.system:
					this._Browsers.CHROME_WEBKIT.version = this._navParts[5].split(' ')[0].substring(8); break;

				default:
					this._Browsers.CHROME_WEBKIT.version = this._navParts[5].split(' ')[0].substring(7);
			}

			output = this._Browsers.CHROME_WEBKIT;
		}

		//chrome/blink
		//They claim they're not going to change the UA in Chrome/Blink 
		//but I don't believe them so instead of waiting for them to secretly
		//change something I'll just assume that anything >=28 is Blink and <28
		//is Webkit
		if(/^Chrome/.test(this._navParts[5]) && /^AppleWebKit/.test(this._navParts[3])){
			this._Browsers.CHROME_BLINK.version = this._navParts[5].substring(7, 19);

			if(parseInt(this._Browsers.CHROME_BLINK.version) >= 28) { //version 28 is first public release with Blink instead of Webkit
				output = this._Browsers.CHROME_BLINK;
			}
		}

		//firefox/gecko
		if(/^(Gecko|Firefox)/.test(this._navParts[4]) || /^(Gecko|Firefox)/.test(this._navParts[5])){ //Linux UA has 6, Windows has 5
			switch(os){
				case this._OS.LINUX.system:
					this._Browsers.FIREFOX.version = this._navParts[5].split(' ')[1].substring(8); break;

				case this._OS.WINDOWS.system:
					this._Browsers.FIREFOX.version = this._navParts[4].split(' ')[1].substring(8); break;
			}
			
			output = this._Browsers.FIREFOX;
		}

		if(/iPad/.test(this._Navigator.userAgent)){
			output = this._Browsers.IPAD;
		} 
		
		//ie/trident <= 10
		if(/^MSIE/.test(this._navParts[2])){
			this._Browsers.IE.engine = this._navParts[5].split('/')[0]; //we can get a little more specific here, so why not
			this._Browsers.IE.version = this._navParts[5].split('/')[1];

			output = this._Browsers.IE;
		}
		
		//ie/trident > 10
		if(/^Trident/.test(this._navParts[3])){
			this._Browsers.IE.engine = this._navParts[3].split("/")[0];
			this._Browsers.IE.version = this._navParts[3].split("/")[1];

			output = this._Browsers.IE;
		}

		//opera/presto
		if(/^Opera/.test(this._navParts[0])){
			output = this._Browsers.OPERA_PRESTO; //preliminary support
		}

		//opera/blink
		

		return output;
	},

	/**
	 * Determine the user's operating system
	 *
	 * @param {object} options [Any required settings]
	 * @since  1.0.0
	 * @return {string}
	 */
	os: function(options){
		var output = {system: this._OS.UNKNOWN.name, architecture: this._OS.UNKNOWN.architecture};
			bits = this._bits();

		switch(this._Navigator.platform.toLowerCase().split(' ')[0] || this._navParts[3].toLowerCase()){
			case 'macintel':
			case 'macppc':
				output.system = this._OS.MAC.name;
				output.architecture = (bits ? bits : this._OS.MAC.bits); break;

			case 'win32':
				output.system = this._OS.WINDOWS.name;
				output.architecture = (bits ? bits : this._OS.WINDOWS.bits); break;

			case 'linux':
				output.system = this._OS.LINUX.name;
				output.architecture = (bits ? bits : this._OS.LINUX.bits); break;
		}

		return output;
	},

	/**
	 * Determine what plugins, if any, the browser is running
	 *
	 * @param {object} options [Any required settings]
	 * @since  1.0.0
	 * @return {object}
	 */
	plugins: function(options){
		var output = {};

		if(this._Navigator.plugins && this._Navigator.plugins.length > 1){
			output.content = [];

			if(options.format){
				//HTML
				for(var i = 0; i < this._Navigator.plugins.length; i++){
					var plugin = this._Navigator.plugins[i];

					if(plugin.name)
						output.content.push('<li>Name: <strong>'+ plugin.name +'</strong></li>');
					if(plugin.description)
						output.content.push('<li>Description: <strong>'+ plugin.description +'</strong></li>');
				}
				output = output.content.join(' ');
			}else {
				//object
				for(var i = 0; i < this._Navigator.plugins.length; i++){
					var plugin = this._Navigator.plugins[i];

					output.content.push({name: plugin.name, description: plugin.description, slug: this._slug(plugin.name)});
				}
			}
		}
		
		return output;
	},

	/**
	 * Determine the CPU architecture - 32 or 64
	 * Note: some browsers don't broadcast the system architecture 
	 * so this will make it's best guess
	 * 
	 * @since  1.0.0
	 * @return {mixed} [bool|string]
	 */
	_bits: function(){
		var knownBits = this._Navigator.platform.toLowerCase().split(' ')[1],
			output = (/(86|64)/.test(knownBits) ? '64' : '32');

		switch(this._Navigator.platform.toLowerCase().split(' ')[0] || this._navParts[3].toLowerCase()){
			case 'macintel':
				output = '64'; break;

			case 'macppc':
				output = '32'; break;

			//not tested yet
			case 'win32': 
				output = (this._navParts[2].match(/wow64/i) ? '64' : '32'); break;

			//not tested yet
			case 'linux': 
				output = '64'; break; //currently an assumption
		}

		return output;
	},

	/**
	 * Add classes to the body element if options.addBodyClasses is true
	 *
	 * @param {object} options
	 * @param {object} ref [The object we use to determine the proper class names]
	 * @since  1.0.0
	 * @return {void}
	 */
	_addClasses: function(options, ref){
		var output = '',
			useDefault = (options.classPrefix === 'default');

		for(var prop in ref){
			if(ref[prop] && typeof ref[prop] === 'object'){
				for(var iprop in ref[prop]){
					if(ref[prop][iprop]){ //ignore undefined or null values
						var html_el = document.querySelector('html');

						if(typeof ref[prop][iprop] === 'string'){ //system, architecture, browser name, browser engine, etc
							if(useDefault){
								html_el.classList.add(iprop +'-'+ ref[prop][iprop].toLowerCase());	
							}else {
								html_el.classList.add(options.classPrefix + iprop +'-'+ ref[prop][iprop].toLowerCase());
							}
						}else { //plugin array
							for(var i = 0, plgs = ref[prop][iprop]; i < plgs.length; i++){
								if(useDefault){
									html_el.classList.add(this._sanitize('plugin-'+ plgs[i].slug.toLowerCase()));
								}else {
									html_el.classList.add(this._sanitize(options.classPrefix + 'plugin-'+ plgs[i].slug.toLowerCase()));
								}
							}
						}
					}
				}
			}
		}
	},

	/**
	 * Generate a slug that is easier to remember than the real plugin name
	 *
	 * @param  {string} plugin [Plugin name to process]
	 * @since  1.0.0
	 * @return {string}
	 */
	_slug: function(plugin){
		return plugin.split(' ').join('_').toLowerCase();
	},

	/**
	 * Sanitize plugin names to remove things like symbols and vesion numbers
	 * 
	 * @param  {string} plugin [Plugin name to process]
	 * @return {string}
	 */
	_sanitize: function(plugin){
		var pattern = new RegExp('[a-zA-Z-_]+', 'gi');
		
		return pattern.exec(plugin);
	},

	/**
	 * Format a string 
	 * TODO: more dynamic formatting
	 * 
	 * @since 1.0.0
	 * @return {String}
	 */
	sprintf: function(src, repl){
		return src.replace('%s', repl);
	},

	/************************************************************
	 * Utility plugins
	 ************************************************************/

	/**
	 * Testing for all possible user agents to ensure accuracy
	 *
	 * @since  1.0.0
	 * @param  {object} context [The Detect object]
	 * @return {object}
	 */
	DJS_Tests: function(context){
		//object of all the browser user agents we are testing for
		var browsers =  [];

		var tests = {
			//tests to run (each one a function)
		}

		
	},

	/**
	 * A set of utility plugins for working with installed plugins
	 * 
	 * @since  1.0.0
	 * @param {object} context [The Detect object]
	 * @type {Object}
	 */
	DJS_PluginUtility: function(DetectObj, output){
		/**
		 * Reference to the Detect object
		 *
		 * @type {object} The Detect object
		 */
		var Detect = output;

		var _Errors = {
			NOT_FOUND: '%s Not Found',
		};

		/**
		 * Determine if a plugin is installed
		 *
		 * @param  {string}  plugin_slug [The slug to compare each installed plugin against]
		 * @since  1.0.0
		 * @return {Boolean}
		 */
		this.isInstalled = function(plugin_slug){
			var found = 0;

			if(plugin_slug && typeof Detect.plugins === 'object'){
				for(var i = 0, plgs = Detect.plugins.content; i < plgs.length; i++){
					if(plugin_slug === plgs[i].slug){
						found++;
					}
				}
			}

			return (found > 0);
		};

		/**
		 * Determine the version of a specified plugin
		 *
		 * @param  {string} plugin_slug [The slug to compare each installed plugin against]
		 * @since  1.0.0
		 * @return {mixed}
		 */
		this.getVersion = function(plugin_slug){
			var pattern = new RegExp("\d+(\d+)?", 'g');

			if(this.isInstalled(plugin_slug)){
				//if there is a version string in either the name or the description, we will use it
				if(pattern.test(plgs[i].name) || pattern.test(plgs[i].description)){
					return plgs[i].description.match(pattern).join('.');
				}
			}

			return DetectObj.sprintf(_Errors.NOT_FOUND, 'Plugin');
		};
	},
};

