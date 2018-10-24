/*! elementor - v2.2.6 - 22-10-2018 */
/*! elementor - v2.2.6 - 22-10-2018 */
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 167);
/******/ })
/************************************************************************/
/******/ ({

/***/ 0:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

var Module = function Module() {
	var $ = jQuery,
	    instanceParams = arguments,
	    self = this,
	    events = {};

	var settings = void 0;

	var ensureClosureMethods = function ensureClosureMethods() {
		$.each(self, function (methodName) {
			var oldMethod = self[methodName];

			if ('function' !== typeof oldMethod) {
				return;
			}

			self[methodName] = function () {
				return oldMethod.apply(self, arguments);
			};
		});
	};

	var initSettings = function initSettings() {
		settings = self.getDefaultSettings();

		var instanceSettings = instanceParams[0];

		if (instanceSettings) {
			$.extend(settings, instanceSettings);
		}
	};

	var init = function init() {
		self.__construct.apply(self, instanceParams);

		ensureClosureMethods();

		initSettings();

		self.trigger('init');
	};

	this.getItems = function (items, itemKey) {
		if (itemKey) {
			var keyStack = itemKey.split('.'),
			    currentKey = keyStack.splice(0, 1);

			if (!keyStack.length) {
				return items[currentKey];
			}

			if (!items[currentKey]) {
				return;
			}

			return this.getItems(items[currentKey], keyStack.join('.'));
		}

		return items;
	};

	this.getSettings = function (setting) {
		return this.getItems(settings, setting);
	};

	this.setSettings = function (settingKey, value, settingsContainer) {
		if (!settingsContainer) {
			settingsContainer = settings;
		}

		if ('object' === (typeof settingKey === 'undefined' ? 'undefined' : _typeof(settingKey))) {
			$.extend(settingsContainer, settingKey);

			return self;
		}

		var keyStack = settingKey.split('.'),
		    currentKey = keyStack.splice(0, 1);

		if (!keyStack.length) {
			settingsContainer[currentKey] = value;

			return self;
		}

		if (!settingsContainer[currentKey]) {
			settingsContainer[currentKey] = {};
		}

		return self.setSettings(keyStack.join('.'), value, settingsContainer[currentKey]);
	};

	this.forceMethodImplementation = function (methodArguments) {
		var functionName = methodArguments.callee.name;

		throw new ReferenceError('The method ' + functionName + ' must to be implemented in the inheritor child.');
	};

	this.on = function (eventName, callback) {
		if ('object' === (typeof eventName === 'undefined' ? 'undefined' : _typeof(eventName))) {
			$.each(eventName, function (singleEventName) {
				self.on(singleEventName, this);
			});

			return self;
		}

		var eventNames = eventName.split(' ');

		eventNames.forEach(function (singleEventName) {
			if (!events[singleEventName]) {
				events[singleEventName] = [];
			}

			events[singleEventName].push(callback);
		});

		return self;
	};

	this.off = function (eventName, callback) {
		if (!events[eventName]) {
			return self;
		}

		if (!callback) {
			delete events[eventName];

			return self;
		}

		var callbackIndex = events[eventName].indexOf(callback);

		if (-1 !== callbackIndex) {
			delete events[eventName][callbackIndex];
		}

		return self;
	};

	this.trigger = function (eventName) {
		var methodName = 'on' + eventName[0].toUpperCase() + eventName.slice(1),
		    params = Array.prototype.slice.call(arguments, 1);

		if (self[methodName]) {
			self[methodName].apply(self, params);
		}

		var callbacks = events[eventName];

		if (!callbacks) {
			return self;
		}

		$.each(callbacks, function (index, callback) {
			callback.apply(self, params);
		});

		return self;
	};

	init();
};

Module.prototype.__construct = function () {};

Module.prototype.getDefaultSettings = function () {
	return {};
};

Module.extendsCount = 0;

Module.extend = function (properties) {
	var $ = jQuery,
	    parent = this;

	var child = function child() {
		return parent.apply(this, arguments);
	};

	$.extend(child, parent);

	child.prototype = Object.create($.extend({}, parent.prototype, properties));

	child.prototype.constructor = child;

	/*
  * Constructor ID is used to set an unique ID
     * to every extend of the Module.
     *
  * It's useful in some cases such as unique
  * listener for frontend handlers.
  */
	var constructorID = ++Module.extendsCount;

	child.prototype.getConstructorID = function () {
		return constructorID;
	};

	child.__super__ = parent.prototype;

	return child;
};

module.exports = Module;

/***/ }),

/***/ 1:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var Module = __webpack_require__(0),
    ViewModule;

ViewModule = Module.extend({
	elements: null,

	getDefaultElements: function getDefaultElements() {
		return {};
	},

	bindEvents: function bindEvents() {},

	onInit: function onInit() {
		this.initElements();

		this.bindEvents();
	},

	initElements: function initElements() {
		this.elements = this.getDefaultElements();
	}
});

module.exports = ViewModule;

/***/ }),

/***/ 10:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var TemplateLibraryLoadingView;

TemplateLibraryLoadingView = Marionette.ItemView.extend({
	id: 'elementor-template-library-loading',

	template: '#tmpl-elementor-template-library-loading'
});

module.exports = TemplateLibraryLoadingView;

/***/ }),

/***/ 167:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

var _hotKeys = __webpack_require__(22);

var _hotKeys2 = _interopRequireDefault(_hotKeys);

var _helpers = __webpack_require__(168);

var _helpers2 = _interopRequireDefault(_helpers);

var _ajax = __webpack_require__(169);

var _ajax2 = _interopRequireDefault(_ajax);

var _assistant = __webpack_require__(170);

var _assistant2 = _interopRequireDefault(_assistant);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var ViewModule = __webpack_require__(1);

var ElementorCommonApp = function (_ViewModule) {
	_inherits(ElementorCommonApp, _ViewModule);

	function ElementorCommonApp() {
		_classCallCheck(this, ElementorCommonApp);

		return _possibleConstructorReturn(this, (ElementorCommonApp.__proto__ || Object.getPrototypeOf(ElementorCommonApp)).apply(this, arguments));
	}

	_createClass(ElementorCommonApp, [{
		key: 'setMarionetteTemplateCompiler',
		value: function setMarionetteTemplateCompiler() {
			Marionette.TemplateCache.prototype.compileTemplate = function (rawTemplate, options) {
				options = {
					evaluate: /<#([\s\S]+?)#>/g,
					interpolate: /{{{([\s\S]+?)}}}/g,
					escape: /{{([^}]+?)}}(?!})/g
				};

				return _.template(rawTemplate, options);
			};
		}
	}, {
		key: 'getDefaultElements',
		value: function getDefaultElements() {
			return {
				$window: jQuery(window),
				$document: jQuery(document),
				$body: jQuery(document.body)
			};
		}
	}, {
		key: 'initComponents',
		value: function initComponents() {
			this.helpers = new _helpers2.default();

			this.hotKeys = new _hotKeys2.default();

			this.hotKeys.bindListener(this.elements.$window);

			this.dialogsManager = new DialogsManager.Instance();

			this.initModules();
		}
	}, {
		key: 'initModules',
		value: function initModules() {
			var _this2 = this;

			var modules = {
				ajax: _ajax2.default,
				assistant: _assistant2.default
			};

			Object.entries(modules).forEach(function (_ref) {
				var _ref2 = _slicedToArray(_ref, 2),
				    name = _ref2[0],
				    moduleClass = _ref2[1];

				return _this2[name] = new moduleClass(_this2.config[name]);
			});
		}
	}, {
		key: 'translate',
		value: function translate(stringKey, context, templateArgs, i18nStack) {
			if (context) {
				i18nStack = this.config[context].i18n;
			}

			if (!i18nStack) {
				i18nStack = this.config.i18n;
			}

			var string = i18nStack[stringKey];

			if (undefined === string) {
				string = stringKey;
			}

			if (templateArgs) {
				string = string.replace(/%(?:(\d+)\$)?s/g, function (match, number) {
					if (!number) {
						number = 1;
					}

					number--;

					return undefined !== templateArgs[number] ? templateArgs[number] : match;
				});
			}

			return string;
		}
	}, {
		key: 'onInit',
		value: function onInit() {
			_get(ElementorCommonApp.prototype.__proto__ || Object.getPrototypeOf(ElementorCommonApp.prototype), 'onInit', this).call(this);

			this.config = elementorCommonConfig;

			this.setMarionetteTemplateCompiler();
		}
	}]);

	return ElementorCommonApp;
}(ViewModule);

window.elementorCommon = new ElementorCommonApp();

elementorCommon.initComponents();

/***/ }),

/***/ 168:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Helpers = function () {
	function Helpers() {
		_classCallCheck(this, Helpers);
	}

	_createClass(Helpers, [{
		key: 'deprecatedMethod',
		value: function deprecatedMethod(methodName, version, replacement) {
			var message = '%c   %c`' + methodName + '` is deprecated since ' + version;

			var style = 'font-size: 12px; background-image: url("' + elementorCommon.config.urls.assets + 'images/logo-icon.png"); background-repeat: no-repeat; background-size: contain;';

			if (replacement) {
				message += ' - Use `' + replacement + '()` instead';
			}

			console.warn(message, style, ''); // eslint-disable-line no-console
		}
	}, {
		key: 'cloneObject',
		value: function cloneObject(object) {
			return JSON.parse(JSON.stringify(object));
		}
	}, {
		key: 'firstLetterUppercase',
		value: function firstLetterUppercase(string) {
			return string[0].toUpperCase() + string.slice(1);
		}
	}]);

	return Helpers;
}();

exports.default = Helpers;

/***/ }),

/***/ 169:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Module = __webpack_require__(0);

var _class = function (_Module) {
	_inherits(_class, _Module);

	_createClass(_class, [{
		key: 'getDefaultSettings',
		value: function getDefaultSettings() {
			return {
				ajaxParams: {
					type: 'POST',
					url: elementorCommon.config.ajax.url,
					data: {}
				},
				actionPrefix: 'elementor_'
			};
		}
	}]);

	function _class() {
		var _ref;

		_classCallCheck(this, _class);

		for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
			args[_key] = arguments[_key];
		}

		var _this = _possibleConstructorReturn(this, (_ref = _class.__proto__ || Object.getPrototypeOf(_class)).call.apply(_ref, [this].concat(args)));

		_this.requests = {};

		_this.cache = {};

		_this.initRequestConstants();

		_this.debounceSendBatch = _.debounce(_this.sendBatch.bind(_this), 500);
		return _this;
	}

	_createClass(_class, [{
		key: 'initRequestConstants',
		value: function initRequestConstants() {
			this.requestConstants = {
				_nonce: this.getSettings('nonce')
			};
		}
	}, {
		key: 'addRequestConstant',
		value: function addRequestConstant(key, value) {
			this.requestConstants[key] = value;
		}
	}, {
		key: 'getCacheKey',
		value: function getCacheKey(request) {
			return JSON.stringify({
				unique_id: request.unique_id,
				data: request.data
			});
		}
	}, {
		key: 'loadObjects',
		value: function loadObjects(options) {
			var _this2 = this;

			var dataCollection = {};

			var deferredArray = [];

			if (options.before) {
				options.before();
			}

			options.ids.forEach(function (objectId) {
				deferredArray.push(_this2.load({
					action: options.action,
					unique_id: options.data.unique_id + objectId,
					data: jQuery.extend({ id: objectId }, options.data)
				}).done(function (data) {
					return dataCollection = jQuery.extend(dataCollection, data);
				}));
			});

			jQuery.when.apply(jQuery, deferredArray).done(function () {
				return options.success(dataCollection);
			});
		}
	}, {
		key: 'load',
		value: function load(request) {
			var _this3 = this;

			if (!request.unique_id) {
				request.unique_id = request.action;
			}

			if (request.before) {
				request.before();
			}

			var deferred = void 0;

			var cacheKey = this.getCacheKey(request);

			if (_.has(this.cache, cacheKey)) {
				deferred = jQuery.Deferred().done(request.success).resolve(this.cache[cacheKey]);
			} else {
				deferred = this.addRequest(request.action, {
					data: request.data,
					unique_id: request.unique_id,
					success: function success(data) {
						return _this3.cache[cacheKey] = data;
					}
				}).done(request.success);
			}

			return deferred;
		}
	}, {
		key: 'addRequest',
		value: function addRequest(action, options, immediately) {
			options = options || {};

			if (!options.unique_id) {
				options.unique_id = action;
			}

			options.deferred = jQuery.Deferred().done(options.success).fail(options.error).always(options.complete);

			var request = {
				action: action,
				options: options
			};

			if (immediately) {
				var requests = {};

				requests[options.unique_id] = request;

				options.deferred.jqXhr = this.sendBatch(requests);
			} else {
				this.requests[options.unique_id] = request;

				this.debounceSendBatch();
			}

			return options.deferred;
		}
	}, {
		key: 'sendBatch',
		value: function sendBatch(requests) {
			var actions = {};

			if (!requests) {
				requests = this.requests;

				// Empty for next batch.
				this.requests = {};
			}

			Object.entries(requests).forEach(function (_ref2) {
				var _ref3 = _slicedToArray(_ref2, 2),
				    id = _ref3[0],
				    request = _ref3[1];

				return actions[id] = {
					action: request.action,
					data: request.options.data
				};
			});

			return this.send('ajax', {
				data: {
					actions: JSON.stringify(actions)
				},
				success: function success(data) {
					Object.entries(data.responses).forEach(function (_ref4) {
						var _ref5 = _slicedToArray(_ref4, 2),
						    id = _ref5[0],
						    response = _ref5[1];

						var options = requests[id].options;

						if (options) {
							if (response.success) {
								options.deferred.resolve(response.data);
							} else if (!response.success) {
								options.deferred.reject(response.data);
							}
						}
					});
				},
				error: function error(data) {
					return Object.values(requests).forEach(function (args) {
						if (args.options) {
							args.options.deferred.reject(data);
						}
					});
				}
			});
		}
	}, {
		key: 'send',
		value: function send(action, options) {
			var _this4 = this;

			var settings = this.getSettings(),
			    ajaxParams = elementorCommon.helpers.cloneObject(settings.ajaxParams);

			options = options || {};

			action = settings.actionPrefix + action;

			jQuery.extend(ajaxParams, options);

			var requestConstants = elementorCommon.helpers.cloneObject(this.requestConstants);

			requestConstants.action = action;

			var isFormData = ajaxParams.data instanceof FormData;

			Object.entries(requestConstants).forEach(function (_ref6) {
				var _ref7 = _slicedToArray(_ref6, 2),
				    key = _ref7[0],
				    value = _ref7[1];

				if (isFormData) {
					ajaxParams.data.append(key, value);
				} else {
					ajaxParams.data[key] = value;
				}
			});

			var successCallback = ajaxParams.success,
			    errorCallback = ajaxParams.error;

			if (successCallback || errorCallback) {
				ajaxParams.success = function (response) {
					if (response.success && successCallback) {
						successCallback(response.data);
					}

					if (!response.success && errorCallback) {
						errorCallback(response.data);
					}
				};

				if (errorCallback) {
					ajaxParams.error = function (data) {
						return errorCallback(data);
					};
				} else {
					ajaxParams.error = function (xmlHttpRequest) {
						if (xmlHttpRequest.readyState || 'abort' !== xmlHttpRequest.statusText) {
							_this4.trigger('request:unhandledError', xmlHttpRequest);
						}
					};
				}
			}

			return jQuery.ajax(ajaxParams);
		}
	}]);

	return _class;
}(Module);

exports.default = _class;

/***/ }),

/***/ 170:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _modalLayout = __webpack_require__(171);

var _modalLayout2 = _interopRequireDefault(_modalLayout);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Module = __webpack_require__(0);

var _class = function (_Module) {
	_inherits(_class, _Module);

	function _class() {
		_classCallCheck(this, _class);

		return _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).apply(this, arguments));
	}

	_createClass(_class, [{
		key: 'initLayout',
		value: function initLayout() {
			var layout = void 0;

			this.getLayout = function () {
				if (!layout) {
					layout = new _modalLayout2.default();
				}

				return layout;
			};
		}
	}, {
		key: 'addShortcut',
		value: function addShortcut() {
			var _this2 = this;

			var E_KEY = 69;

			elementorCommon.hotKeys.addHotKeyHandler(E_KEY, 'assistant', {
				isWorthHandling: function isWorthHandling(event) {
					return elementorCommon.hotKeys.isControlEvent(event);
				},
				handle: function handle() {
					return _this2.getLayout().showModal();
				}
			});
		}
	}, {
		key: 'onInit',
		value: function onInit() {
			this.channel = Backbone.Radio.channel('ELEMENTOR:assistant');

			this.initLayout();

			this.addShortcut();
		}
	}]);

	return _class;
}(Module);

exports.default = _class;

/***/ }),

/***/ 171:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

var _modalContent = __webpack_require__(172);

var _modalContent2 = _interopRequireDefault(_modalContent);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var BaseModalLayout = __webpack_require__(5);

var _class = function (_BaseModalLayout) {
	_inherits(_class, _BaseModalLayout);

	function _class() {
		_classCallCheck(this, _class);

		return _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).apply(this, arguments));
	}

	_createClass(_class, [{
		key: 'getModalOptions',
		value: function getModalOptions() {
			return {
				id: 'elementor-assistant__modal',
				position: {
					enable: false
				}
			};
		}
	}, {
		key: 'getLogoOptions',
		value: function getLogoOptions() {
			return {
				title: elementorCommon.translate('assistant', 'assistant')
			};
		}
	}, {
		key: 'getHeaderOptions',
		value: function getHeaderOptions() {
			return {
				closeType: false
			};
		}
	}, {
		key: 'initialize',
		value: function initialize() {
			var _get2;

			for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
				args[_key] = arguments[_key];
			}

			(_get2 = _get(_class.prototype.__proto__ || Object.getPrototypeOf(_class.prototype), 'initialize', this)).call.apply(_get2, [this].concat(args));

			this.showLogo();

			this.showContentView();
		}
	}, {
		key: 'showContentView',
		value: function showContentView() {
			this.modalContent.show(new _modalContent2.default());
		}
	}, {
		key: 'showModal',
		value: function showModal() {
			var _get3;

			for (var _len2 = arguments.length, args = Array(_len2), _key2 = 0; _key2 < _len2; _key2++) {
				args[_key2] = arguments[_key2];
			}

			(_get3 = _get(_class.prototype.__proto__ || Object.getPrototypeOf(_class.prototype), 'showModal', this)).call.apply(_get3, [this].concat(args));

			this.modalContent.currentView.ui.searchInput.focus();
		}
	}]);

	return _class;
}(BaseModalLayout);

exports.default = _class;

/***/ }),

/***/ 172:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _categories = __webpack_require__(173);

var _categories2 = _interopRequireDefault(_categories);

var _start = __webpack_require__(177);

var _start2 = _interopRequireDefault(_start);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var _class = function (_Marionette$LayoutVie) {
	_inherits(_class, _Marionette$LayoutVie);

	function _class() {
		_classCallCheck(this, _class);

		return _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).apply(this, arguments));
	}

	_createClass(_class, [{
		key: 'id',
		value: function id() {
			return 'elementor-assistant';
		}
	}, {
		key: 'getTemplate',
		value: function getTemplate() {
			return '#tmpl-elementor-assistant';
		}
	}, {
		key: 'ui',
		value: function ui() {
			return {
				searchInput: '#elementor-assistant__search__input'
			};
		}
	}, {
		key: 'events',
		value: function events() {
			return {
				'input @ui.searchInput': 'onSearchInputInput'
			};
		}
	}, {
		key: 'regions',
		value: function regions() {
			return {
				content: '#elementor-assistant__content'
			};
		}
	}, {
		key: 'showStartView',
		value: function showStartView() {
			this.content.show(new _start2.default());
		}
	}, {
		key: 'showCategoriesView',
		value: function showCategoriesView() {
			this.content.show(new _categories2.default());
		}
	}, {
		key: 'onShow',
		value: function onShow() {
			this.showStartView();
		}
	}, {
		key: 'onSearchInputInput',
		value: function onSearchInputInput() {
			var value = this.ui.searchInput.val();

			elementorCommon.assistant.channel.reply('filter:text', value).trigger('filter:change');

			if (value) {
				if (!(this.content.currentView instanceof _categories2.default)) {
					this.showCategoriesView();
				}
			} else {
				this.showStartView();
			}
		}
	}]);

	return _class;
}(Marionette.LayoutView);

exports.default = _class;

/***/ }),

/***/ 173:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _category = __webpack_require__(46);

var _category2 = _interopRequireDefault(_category);

var _remoteCategory = __webpack_require__(176);

var _remoteCategory2 = _interopRequireDefault(_remoteCategory);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var _class = function (_Marionette$Composite) {
	_inherits(_class, _Marionette$Composite);

	function _class() {
		_classCallCheck(this, _class);

		return _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).apply(this, arguments));
	}

	_createClass(_class, [{
		key: 'id',
		value: function id() {
			return 'elementor-assistant__results-container';
		}
	}, {
		key: 'ui',
		value: function ui() {
			return {
				noResults: '#elementor-assistant__no-results'
			};
		}
	}, {
		key: 'getTemplate',
		value: function getTemplate() {
			return '#tmpl-elementor-assistant-results-container';
		}
	}, {
		key: 'getChildView',
		value: function getChildView(childModel) {
			return childModel.get('remote') ? _remoteCategory2.default : _category2.default;
		}
	}, {
		key: 'initialize',
		value: function initialize() {
			this.childViewContainer = '#elementor-assistant__results';

			this.collection = new Backbone.Collection(elementorCommon.assistant.getSettings('data'));
		}
	}, {
		key: 'onChildviewToggleVisibility',
		value: function onChildviewToggleVisibility() {
			var allCategoriesAreEmpty = this.children.every(function (child) {
				return !child.isVisible;
			});

			this.ui.noResults.toggle(allCategoriesAreEmpty);
		}
	}]);

	return _class;
}(Marionette.CompositeView);

exports.default = _class;

/***/ }),

/***/ 174:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var _class = function (_Marionette$ItemView) {
	_inherits(_class, _Marionette$ItemView);

	function _class() {
		_classCallCheck(this, _class);

		return _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).apply(this, arguments));
	}

	_createClass(_class, [{
		key: 'className',
		value: function className() {
			return 'elementor-assistant__results__item';
		}
	}, {
		key: 'getTemplate',
		value: function getTemplate() {
			return '#tmpl-elementor-assistant__results__item';
		}
	}, {
		key: 'tagName',
		value: function tagName() {
			if (this.model.get('link')) {
				return 'a';
			}

			return 'div';
		}
	}, {
		key: 'attributes',
		value: function attributes() {
			var attributes = {},
			    link = this.model.get('link');

			if (link) {
				attributes.href = link;
				attributes.target = '_blank';
			}

			return attributes;
		}
	}]);

	return _class;
}(Marionette.ItemView);

exports.default = _class;

/***/ }),

/***/ 175:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var _class = function (_Backbone$Model) {
	_inherits(_class, _Backbone$Model);

	function _class() {
		_classCallCheck(this, _class);

		return _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).apply(this, arguments));
	}

	_createClass(_class, [{
		key: 'defaults',
		value: function defaults() {
			return {
				description: '',
				icon: '',
				link: ''
			};
		}
	}]);

	return _class;
}(Backbone.Model);

exports.default = _class;

/***/ }),

/***/ 176:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

var _category = __webpack_require__(46);

var _category2 = _interopRequireDefault(_category);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var _class = function (_Category) {
	_inherits(_class, _Category);

	function _class() {
		_classCallCheck(this, _class);

		return _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).apply(this, arguments));
	}

	_createClass(_class, [{
		key: 'ui',
		value: function ui() {
			return {
				title: '.elementor-assistant__results__category__title'
			};
		}
	}, {
		key: 'fetchData',
		value: function fetchData() {
			var _this2 = this;

			elementorCommon.ajax.addRequest('assistant_get_category_data', {
				data: {
					category: this.model.get('name'),
					filter: this.getTextFilter()
				},
				success: function success(data) {
					_this2.collection.set(data);

					_this2.toggleElement();
				}
			});
		}
	}, {
		key: 'filter',
		value: function filter() {
			return true;
		}
	}, {
		key: 'onFilterChange',
		value: function onFilterChange() {
			this.fetchData();
		}
	}, {
		key: 'onRender',
		value: function onRender() {
			_get(_class.prototype.__proto__ || Object.getPrototypeOf(_class.prototype), 'onRender', this).call(this);

			this.fetchData();
		}
	}]);

	return _class;
}(_category2.default);

exports.default = _class;

/***/ }),

/***/ 177:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var _class = function (_Marionette$ItemView) {
	_inherits(_class, _Marionette$ItemView);

	function _class() {
		_classCallCheck(this, _class);

		return _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).apply(this, arguments));
	}

	_createClass(_class, [{
		key: "getTemplate",
		value: function getTemplate() {
			return false;
		}
	}]);

	return _class;
}(Marionette.ItemView);

exports.default = _class;

/***/ }),

/***/ 22:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _environment = __webpack_require__(3);

var _environment2 = _interopRequireDefault(_environment);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var HotKeys = function () {
	function HotKeys() {
		_classCallCheck(this, HotKeys);

		this.hotKeysHandlers = {};
	}

	_createClass(HotKeys, [{
		key: 'applyHotKey',
		value: function applyHotKey(event) {
			var handlers = this.hotKeysHandlers[event.which];

			if (!handlers) {
				return;
			}

			jQuery.each(handlers, function (key, handler) {
				if (handler.isWorthHandling && !handler.isWorthHandling(event)) {
					return;
				}

				// Fix for some keyboard sources that consider alt key as ctrl key
				if (!handler.allowAltKey && event.altKey) {
					return;
				}

				event.preventDefault();

				handler.handle(event);
			});
		}
	}, {
		key: 'isControlEvent',
		value: function isControlEvent(event) {
			return event[_environment2.default.mac ? 'metaKey' : 'ctrlKey'];
		}
	}, {
		key: 'addHotKeyHandler',
		value: function addHotKeyHandler(keyCode, handlerName, handler) {
			if (!this.hotKeysHandlers[keyCode]) {
				this.hotKeysHandlers[keyCode] = {};
			}

			this.hotKeysHandlers[keyCode][handlerName] = handler;
		}
	}, {
		key: 'bindListener',
		value: function bindListener($listener) {
			$listener.on('keydown', this.applyHotKey.bind(this));
		}
	}]);

	return HotKeys;
}();

exports.default = HotKeys;

/***/ }),

/***/ 3:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});
var userAgent = navigator.userAgent;

exports.default = {
	webkit: -1 !== userAgent.indexOf('AppleWebKit'),
	firefox: -1 !== userAgent.indexOf('Firefox'),
	ie: /Trident|MSIE/.test(userAgent),
	edge: -1 !== userAgent.indexOf('Edge'),
	mac: -1 !== userAgent.indexOf('Macintosh')
};

/***/ }),

/***/ 46:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _item = __webpack_require__(174);

var _item2 = _interopRequireDefault(_item);

var _itemModel = __webpack_require__(175);

var _itemModel2 = _interopRequireDefault(_itemModel);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var _class = function (_Marionette$Composite) {
	_inherits(_class, _Marionette$Composite);

	function _class() {
		_classCallCheck(this, _class);

		return _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).apply(this, arguments));
	}

	_createClass(_class, [{
		key: 'className',
		value: function className() {
			return 'elementor-assistant__results__category';
		}
	}, {
		key: 'getTemplate',
		value: function getTemplate() {
			return '#tmpl-elementor-assistant__results__category';
		}
	}, {
		key: 'getChildView',
		value: function getChildView() {
			return _item2.default;
		}
	}, {
		key: 'initialize',
		value: function initialize() {
			this.childViewContainer = '.elementor-assistant__results__category__items';

			this.isVisible = true;

			this.collection = new Backbone.Collection(this.model.get('items'), { model: _itemModel2.default });
		}
	}, {
		key: 'filter',
		value: function filter(childModel) {
			return childModel.get('title').toLowerCase().indexOf(this.getTextFilter()) >= 0;
		}
	}, {
		key: 'getTextFilter',
		value: function getTextFilter() {
			return elementorCommon.assistant.channel.request('filter:text').trim().toLowerCase();
		}
	}, {
		key: 'toggleElement',
		value: function toggleElement() {
			var isCurrentlyVisible = !!this.children.length;

			if (isCurrentlyVisible !== this.isVisible) {
				this.isVisible = isCurrentlyVisible;

				this.$el.toggle(isCurrentlyVisible);

				this.triggerMethod('toggle:visibility');
			}
		}
	}, {
		key: 'onRender',
		value: function onRender() {
			this.listenTo(elementorCommon.assistant.channel, 'filter:change', this.onFilterChange.bind(this));
		}
	}, {
		key: 'onFilterChange',
		value: function onFilterChange() {
			this._renderChildren();
		}
	}, {
		key: 'onRenderCollection',
		value: function onRenderCollection() {
			this.toggleElement();
		}
	}]);

	return _class;
}(Marionette.CompositeView);

exports.default = _class;

/***/ }),

/***/ 5:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var TemplateLibraryHeaderView = __webpack_require__(9),
    TemplateLibraryHeaderLogoView = __webpack_require__(8),
    TemplateLibraryLoadingView = __webpack_require__(10);

module.exports = Marionette.LayoutView.extend({
	el: function el() {
		return this.modal.getElements('widget');
	},

	modal: null,

	regions: function regions() {
		return {
			modalHeader: '.dialog-header',
			modalContent: '.dialog-lightbox-content',
			modalLoading: '.dialog-lightbox-loading'
		};
	},

	constructor: function constructor() {
		this.initModal();

		Marionette.LayoutView.prototype.constructor.apply(this, arguments);
	},

	initialize: function initialize() {
		this.modalHeader.show(new TemplateLibraryHeaderView(this.getHeaderOptions()));
	},

	initModal: function initModal() {
		var modalOptions = {
			className: 'elementor-templates-modal',
			closeButton: false,
			hide: {
				onOutsideClick: false
			}
		};

		jQuery.extend(true, modalOptions, this.getModalOptions());

		this.modal = elementorCommon.dialogsManager.createWidget('lightbox', modalOptions);

		this.modal.getElements('message').append(this.modal.addElement('content'), this.modal.addElement('loading'));
	},

	showModal: function showModal() {
		this.modal.show();
	},

	hideModal: function hideModal() {
		this.modal.hide();
	},

	getModalOptions: function getModalOptions() {
		return {};
	},

	getLogoOptions: function getLogoOptions() {
		return {};
	},

	getHeaderOptions: function getHeaderOptions() {
		return {
			closeType: 'normal'
		};
	},

	getHeaderView: function getHeaderView() {
		return this.modalHeader.currentView;
	},

	showLoadingView: function showLoadingView() {
		this.modalLoading.show(new TemplateLibraryLoadingView());

		this.modalLoading.$el.show();

		this.modalContent.$el.hide();
	},

	hideLoadingView: function hideLoadingView() {
		this.modalContent.$el.show();

		this.modalLoading.$el.hide();
	},

	showLogo: function showLogo() {
		this.getHeaderView().logoArea.show(new TemplateLibraryHeaderLogoView(this.getLogoOptions()));
	}
});

/***/ }),

/***/ 8:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = Marionette.ItemView.extend({
	template: '#tmpl-elementor-templates-modal__header__logo',

	className: 'elementor-templates-modal__header__logo',

	events: {
		click: 'onClick'
	},

	templateHelpers: function templateHelpers() {
		return {
			title: this.getOption('title')
		};
	},

	onClick: function onClick() {
		var clickCallback = this.getOption('click');

		if (clickCallback) {
			clickCallback();
		}
	}
});

/***/ }),

/***/ 9:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var TemplateLibraryHeaderView;

TemplateLibraryHeaderView = Marionette.LayoutView.extend({

	className: 'elementor-templates-modal__header',

	template: '#tmpl-elementor-templates-modal__header',

	regions: {
		logoArea: '.elementor-templates-modal__header__logo-area',
		tools: '#elementor-template-library-header-tools',
		menuArea: '.elementor-templates-modal__header__menu-area'
	},

	ui: {
		closeModal: '.elementor-templates-modal__header__close'
	},

	events: {
		'click @ui.closeModal': 'onCloseModalClick'
	},

	templateHelpers: function templateHelpers() {
		return {
			closeType: this.getOption('closeType')
		};
	},

	onCloseModalClick: function onCloseModalClick() {
		this._parent._parent._parent.hideModal();
	}
});

module.exports = TemplateLibraryHeaderView;

/***/ })

/******/ });
//# sourceMappingURL=common.js.map