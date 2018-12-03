/*! elementor - v2.3.4 - 29-11-2018 */
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
		key: 'id',
		value: function id() {
			return 'elementor-template-library-loading';
		}
	}, {
		key: 'getTemplate',
		value: function getTemplate() {
			return '#tmpl-elementor-template-library-loading';
		}
	}]);

	return _class;
}(Marionette.ItemView);

exports.default = _class;

/***/ }),

/***/ 167:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

var _hotKeys = __webpack_require__(22);

var _hotKeys2 = _interopRequireDefault(_hotKeys);

var _helpers = __webpack_require__(168);

var _helpers2 = _interopRequireDefault(_helpers);

var _ajax = __webpack_require__(169);

var _ajax2 = _interopRequireDefault(_ajax);

var _finder = __webpack_require__(170);

var _finder2 = _interopRequireDefault(_finder);

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

			var activeModules = this.config.activeModules;


			var modules = {
				ajax: _ajax2.default,
				finder: _finder2.default
			};

			activeModules.forEach(function (name) {
				if (modules[name]) {
					_this2[name] = new modules[name](_this2.config[name]);
				}
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

			elementorCommon.hotKeys.addHotKeyHandler(E_KEY, 'finder', {
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
			this.channel = Backbone.Radio.channel('ELEMENTOR:finder');

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

var _layout = __webpack_require__(5);

var _layout2 = _interopRequireDefault(_layout);

var _modalContent = __webpack_require__(172);

var _modalContent2 = _interopRequireDefault(_modalContent);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

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
				id: 'elementor-finder__modal',
				draggable: true,
				effects: {
					show: 'show',
					hide: 'hide'
				},
				position: {
					enable: false
				}
			};
		}
	}, {
		key: 'getLogoOptions',
		value: function getLogoOptions() {
			return {
				title: elementorCommon.translate('finder', 'finder')
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
}(_layout2.default);

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
			return 'elementor-finder';
		}
	}, {
		key: 'getTemplate',
		value: function getTemplate() {
			return '#tmpl-elementor-finder';
		}
	}, {
		key: 'ui',
		value: function ui() {
			return {
				searchInput: '#elementor-finder__search__input'
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
				content: '#elementor-finder__content'
			};
		}
	}, {
		key: 'showCategoriesView',
		value: function showCategoriesView() {
			this.content.show(new _categories2.default());
		}
	}, {
		key: 'onSearchInputInput',
		value: function onSearchInputInput() {
			var value = this.ui.searchInput.val();

			if (value) {
				elementorCommon.finder.channel.reply('filter:text', value).trigger('filter:change');

				if (!(this.content.currentView instanceof _categories2.default)) {
					this.showCategoriesView();
				}
			}

			this.content.currentView.$el.toggle(!!value);
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

var _dynamicCategory = __webpack_require__(176);

var _dynamicCategory2 = _interopRequireDefault(_dynamicCategory);

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
			return 'elementor-finder__results-container';
		}
	}, {
		key: 'ui',
		value: function ui() {
			return {
				noResults: '#elementor-finder__no-results',
				categoryItem: '.elementor-finder__results__item'
			};
		}
	}, {
		key: 'events',
		value: function events() {
			return {
				'mouseenter @ui.categoryItem': 'onCategoryItemMouseEnter'
			};
		}
	}, {
		key: 'getTemplate',
		value: function getTemplate() {
			return '#tmpl-elementor-finder-results-container';
		}
	}, {
		key: 'getChildView',
		value: function getChildView(childModel) {
			return childModel.get('dynamic') ? _dynamicCategory2.default : _category2.default;
		}
	}, {
		key: 'initialize',
		value: function initialize() {
			this.$activeItem = null;

			this.childViewContainer = '#elementor-finder__results';

			this.collection = new Backbone.Collection(Object.values(elementorCommon.finder.getSettings('data')));

			this.addHotKeys();
		}
	}, {
		key: 'activateItem',
		value: function activateItem($item) {
			if (this.$activeItem) {
				this.$activeItem.removeClass('elementor-active');
			}

			$item.addClass('elementor-active');

			this.$activeItem = $item;
		}
	}, {
		key: 'activateNextItem',
		value: function activateNextItem(reverse) {
			var $allItems = jQuery(this.ui.categoryItem.selector);

			var nextItemIndex = 0;

			if (this.$activeItem) {
				nextItemIndex = $allItems.index(this.$activeItem) + (reverse ? -1 : 1);

				if (nextItemIndex >= $allItems.length) {
					nextItemIndex = 0;
				} else if (nextItemIndex < 0) {
					nextItemIndex = $allItems.length - 1;
				}
			}

			var $nextItem = $allItems.eq(nextItemIndex);

			this.activateItem($nextItem);

			$nextItem[0].scrollIntoView({ block: 'nearest' });
		}
	}, {
		key: 'goToActiveItem',
		value: function goToActiveItem(event) {
			var $a = this.$activeItem.children('a'),
			    isControlClicked = elementorCommon.hotKeys.isControlEvent(event);

			if (isControlClicked) {
				$a.attr('target', '_blank');
			}

			$a[0].click();

			if (isControlClicked) {
				$a.removeAttr('target');
			}
		}
	}, {
		key: 'addHotKeys',
		value: function addHotKeys() {
			var _this2 = this;

			var DOWN_ARROW = 40,
			    UP_ARROW = 38,
			    ENTER = 13;

			elementorCommon.hotKeys.addHotKeyHandler(DOWN_ARROW, 'finderNextItem', {
				isWorthHandling: function isWorthHandling() {
					return elementorCommon.finder.getLayout().getModal().isVisible();
				},
				handle: function handle() {
					return _this2.activateNextItem();
				}
			});

			elementorCommon.hotKeys.addHotKeyHandler(UP_ARROW, 'finderPreviousItem', {
				isWorthHandling: function isWorthHandling() {
					return elementorCommon.finder.getLayout().getModal().isVisible();
				},
				handle: function handle() {
					return _this2.activateNextItem(true);
				}
			});

			elementorCommon.hotKeys.addHotKeyHandler(ENTER, 'finderSelectItem', {
				isWorthHandling: function isWorthHandling() {
					return elementorCommon.finder.getLayout().getModal().isVisible() && _this2.$activeItem;
				},
				handle: function handle(event) {
					return _this2.goToActiveItem(event);
				}
			});
		}
	}, {
		key: 'onCategoryItemMouseEnter',
		value: function onCategoryItemMouseEnter(event) {
			this.activateItem(jQuery(event.currentTarget));
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
			return 'elementor-finder__results__item';
		}
	}, {
		key: 'getTemplate',
		value: function getTemplate() {
			return '#tmpl-elementor-finder__results__item';
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
				icon: 'settings',
				url: '',
				keywords: [],
				actions: []
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
		key: 'className',
		value: function className() {
			return _get(_class.prototype.__proto__ || Object.getPrototypeOf(_class.prototype), 'className', this).call(this) + ' elementor-finder__results__category--dynamic';
		}
	}, {
		key: 'ui',
		value: function ui() {
			return {
				title: '.elementor-finder__results__category__title'
			};
		}
	}, {
		key: 'fetchData',
		value: function fetchData() {
			var _this2 = this;

			this.ui.loadingIcon.show();

			elementorCommon.ajax.addRequest('finder_get_category_items', {
				data: {
					category: this.model.get('name'),
					filter: this.getTextFilter()
				},
				success: function success(data) {
					if (_this2.isDestroyed) {
						return;
					}

					_this2.collection.set(data);

					_this2.toggleElement();

					_this2.ui.loadingIcon.hide();
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

			this.ui.loadingIcon = jQuery('<i>', { class: 'eicon-loading eicon-animation-spin' });

			this.ui.title.after(this.ui.loadingIcon);

			this.fetchData();
		}
	}]);

	return _class;
}(_category2.default);

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
			return 'elementor-finder__results__category';
		}
	}, {
		key: 'getTemplate',
		value: function getTemplate() {
			return '#tmpl-elementor-finder__results__category';
		}
	}, {
		key: 'getChildView',
		value: function getChildView() {
			return _item2.default;
		}
	}, {
		key: 'initialize',
		value: function initialize() {
			this.childViewContainer = '.elementor-finder__results__category__items';

			this.isVisible = true;

			var items = this.model.get('items');

			if (items) {
				items = Object.values(items);
			}

			this.collection = new Backbone.Collection(items, { model: _itemModel2.default });
		}
	}, {
		key: 'filter',
		value: function filter(childModel) {
			var textFilter = this.getTextFilter();

			if (childModel.get('title').toLowerCase().indexOf(textFilter) >= 0) {
				return true;
			}

			return childModel.get('keywords').some(function (keyword) {
				return keyword.indexOf(textFilter) >= 0;
			});
		}
	}, {
		key: 'getTextFilter',
		value: function getTextFilter() {
			return elementorCommon.finder.channel.request('filter:text').trim().toLowerCase();
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
			this.listenTo(elementorCommon.finder.channel, 'filter:change', this.onFilterChange.bind(this));
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


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _header = __webpack_require__(9);

var _header2 = _interopRequireDefault(_header);

var _logo = __webpack_require__(8);

var _logo2 = _interopRequireDefault(_logo);

var _loading = __webpack_require__(10);

var _loading2 = _interopRequireDefault(_loading);

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
		key: 'el',
		value: function el() {
			return this.getModal().getElements('widget');
		}
	}, {
		key: 'regions',
		value: function regions() {
			return {
				modalHeader: '.dialog-header',
				modalContent: '.dialog-lightbox-content',
				modalLoading: '.dialog-lightbox-loading'
			};
		}
	}, {
		key: 'initialize',
		value: function initialize() {
			this.modalHeader.show(new _header2.default(this.getHeaderOptions()));
		}
	}, {
		key: 'getModal',
		value: function getModal() {
			if (!this.modal) {
				this.initModal();
			}

			return this.modal;
		}
	}, {
		key: 'initModal',
		value: function initModal() {
			var modalOptions = {
				className: 'elementor-templates-modal',
				closeButton: false,
				draggable: false,
				hide: {
					onOutsideClick: false
				}
			};

			jQuery.extend(true, modalOptions, this.getModalOptions());

			this.modal = elementorCommon.dialogsManager.createWidget('lightbox', modalOptions);

			this.modal.getElements('message').append(this.modal.addElement('content'), this.modal.addElement('loading'));

			if (modalOptions.draggable) {
				this.draggableModal();
			}
		}
	}, {
		key: 'showModal',
		value: function showModal() {
			this.getModal().show();
		}
	}, {
		key: 'hideModal',
		value: function hideModal() {
			this.getModal().hide();
		}
	}, {
		key: 'draggableModal',
		value: function draggableModal() {
			var $modalWidgetContent = this.getModal().getElements('widgetContent');

			$modalWidgetContent.draggable({
				containment: 'parent',
				stop: function stop() {
					$modalWidgetContent.height('');
				}
			});

			$modalWidgetContent.css('position', 'absolute');
		}
	}, {
		key: 'getModalOptions',
		value: function getModalOptions() {
			return {};
		}
	}, {
		key: 'getLogoOptions',
		value: function getLogoOptions() {
			return {};
		}
	}, {
		key: 'getHeaderOptions',
		value: function getHeaderOptions() {
			return {
				closeType: 'normal'
			};
		}
	}, {
		key: 'getHeaderView',
		value: function getHeaderView() {
			return this.modalHeader.currentView;
		}
	}, {
		key: 'showLoadingView',
		value: function showLoadingView() {
			this.modalLoading.show(new _loading2.default());

			this.modalLoading.$el.show();

			this.modalContent.$el.hide();
		}
	}, {
		key: 'hideLoadingView',
		value: function hideLoadingView() {
			this.modalContent.$el.show();

			this.modalLoading.$el.hide();
		}
	}, {
		key: 'showLogo',
		value: function showLogo() {
			this.getHeaderView().logoArea.show(new _logo2.default(this.getLogoOptions()));
		}
	}]);

	return _class;
}(Marionette.LayoutView);

exports.default = _class;

/***/ }),

/***/ 8:
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
		key: 'getTemplate',
		value: function getTemplate() {
			return '#tmpl-elementor-templates-modal__header__logo';
		}
	}, {
		key: 'className',
		value: function className() {
			return 'elementor-templates-modal__header__logo';
		}
	}, {
		key: 'events',
		value: function events() {
			return {
				click: 'onClick'
			};
		}
	}, {
		key: 'templateHelpers',
		value: function templateHelpers() {
			return {
				title: this.getOption('title')
			};
		}
	}, {
		key: 'onClick',
		value: function onClick() {
			var clickCallback = this.getOption('click');

			if (clickCallback) {
				clickCallback();
			}
		}
	}]);

	return _class;
}(Marionette.ItemView);

exports.default = _class;

/***/ }),

/***/ 9:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

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
		key: 'className',
		value: function className() {
			return 'elementor-templates-modal__header';
		}
	}, {
		key: 'getTemplate',
		value: function getTemplate() {
			return '#tmpl-elementor-templates-modal__header';
		}
	}, {
		key: 'regions',
		value: function regions() {
			return {
				logoArea: '.elementor-templates-modal__header__logo-area',
				tools: '#elementor-template-library-header-tools',
				menuArea: '.elementor-templates-modal__header__menu-area'
			};
		}
	}, {
		key: 'ui',
		value: function ui() {
			return {
				closeModal: '.elementor-templates-modal__header__close'
			};
		}
	}, {
		key: 'events',
		value: function events() {
			return {
				'click @ui.closeModal': 'onCloseModalClick'
			};
		}
	}, {
		key: 'templateHelpers',
		value: function templateHelpers() {
			return {
				closeType: this.getOption('closeType')
			};
		}
	}, {
		key: 'onCloseModalClick',
		value: function onCloseModalClick() {
			this._parent._parent._parent.hideModal();
		}
	}]);

	return _class;
}(Marionette.LayoutView);

exports.default = _class;

/***/ })

/******/ });
//# sourceMappingURL=common.js.map