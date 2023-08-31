/******/ (() => { // webpackBootstrap
/******/ 	// runtime can't be in strict mode because a global variable is assign and maybe created.
/******/ 	var __webpack_modules__ = ({

/***/ "./src/common/index.ts":
/*!*****************************!*\
  !*** ./src/common/index.ts ***!
  \*****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var flarum_common_app__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! flarum/common/app */ "flarum/common/app");
/* harmony import */ var flarum_common_app__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(flarum_common_app__WEBPACK_IMPORTED_MODULE_0__);

flarum_common_app__WEBPACK_IMPORTED_MODULE_0___default().initializers.add('xypp/pay-to-read', function () {});

/***/ }),

/***/ "./src/common/transUtil.ts":
/*!*********************************!*\
  !*** ./src/common/transUtil.ts ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   transStr2Str: () => (/* binding */ transStr2Str)
/* harmony export */ });
function transStr2Str(str) {
  if (typeof str === "string") {
    return str;
  } else if (Array.isArray(str)) {
    return str.join("");
  } else {
    return str.toString();
  }
}

/***/ }),

/***/ "./src/forum/components/PayModal.js":
/*!******************************************!*\
  !*** ./src/forum/components/PayModal.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ PayModal)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/inheritsLoose */ "./node_modules/@babel/runtime/helpers/esm/inheritsLoose.js");
/* harmony import */ var flarum_common_components_Modal__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! flarum/common/components/Modal */ "flarum/common/components/Modal");
/* harmony import */ var flarum_common_components_Modal__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(flarum_common_components_Modal__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var flarum_forum_app__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! flarum/forum/app */ "flarum/forum/app");
/* harmony import */ var flarum_forum_app__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(flarum_forum_app__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var flarum_components_Button__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! flarum/components/Button */ "flarum/components/Button");
/* harmony import */ var flarum_components_Button__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(flarum_components_Button__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var flarum_common_utils_setRouteWithForcedRefresh__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! flarum/common/utils/setRouteWithForcedRefresh */ "flarum/common/utils/setRouteWithForcedRefresh");
/* harmony import */ var flarum_common_utils_setRouteWithForcedRefresh__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(flarum_common_utils_setRouteWithForcedRefresh__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var flarum_common_components_Alert_Alert__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! flarum/common/components/Alert/Alert */ "flarum/common/components/Alert/Alert");
/* harmony import */ var flarum_common_components_Alert_Alert__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(flarum_common_components_Alert_Alert__WEBPACK_IMPORTED_MODULE_5__);






var PayModal = /*#__PURE__*/function (_Modal) {
  (0,_babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__["default"])(PayModal, _Modal);
  function PayModal() {
    var _this;
    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }
    _this = _Modal.call.apply(_Modal, [this].concat(args)) || this;
    _this.loading = false;
    _this.tipText = flarum_forum_app__WEBPACK_IMPORTED_MODULE_2___default().translator.trans('xypp-pay-to-read.forum.payment.loading');
    _this.btnText = flarum_forum_app__WEBPACK_IMPORTED_MODULE_2___default().translator.trans('xypp-pay-to-read.forum.payment.loading');
    _this.item_id = -1;
    return _this;
  }
  var _proto = PayModal.prototype;
  _proto.className = function className() {
    return 'Modal--small PayModal';
  };
  _proto.title = function title() {
    return flarum_forum_app__WEBPACK_IMPORTED_MODULE_2___default().translator.trans('xypp-pay-to-read.forum.payment.title');
  };
  _proto.content = function content() {
    return m("div", {
      className: "Modal-body"
    }, this.tipText, m("div", {
      className: "paymodal-btn"
    }, m((flarum_components_Button__WEBPACK_IMPORTED_MODULE_3___default()), {
      "class": "Button Button--primary",
      loading: this.loading,
      disabled: this.loading,
      onclick: this.pay,
      "data-id": this.item_id
    }, this.btnText)));
  };
  _proto.onready = function onready() {
    var _this2 = this;
    this.loading = true;
    this.item_id = this.attrs['item_id'];
    flarum_forum_app__WEBPACK_IMPORTED_MODULE_2___default().request({
      url: flarum_forum_app__WEBPACK_IMPORTED_MODULE_2___default().forum.attribute('apiUrl') + '/pay-to-read/payment/',
      params: {
        id: this.attrs['item_id']
      },
      method: 'GET'
    }).then(function (result) {
      if (result.code == "200") {
        _this2.loading = false;
        _this2.tipText = flarum_forum_app__WEBPACK_IMPORTED_MODULE_2___default().translator.trans('xypp-pay-to-read.forum.payment.tip', [result.ammount, result.user_money]);
        _this2.btnText = flarum_forum_app__WEBPACK_IMPORTED_MODULE_2___default().translator.trans('xypp-pay-to-read.forum.payment.btn');
        m.redraw();
      } else {
        flarum_forum_app__WEBPACK_IMPORTED_MODULE_2___default().modal.close();
        flarum_forum_app__WEBPACK_IMPORTED_MODULE_2___default().alerts.show({
          type: 'error'
        }, flarum_forum_app__WEBPACK_IMPORTED_MODULE_2___default().translator.trans('xypp-pay-to-read.forum.error.' + result.status, []));
      }
    })["catch"](function (e) {
      flarum_forum_app__WEBPACK_IMPORTED_MODULE_2___default().alerts.show({
        type: 'error'
      }, flarum_forum_app__WEBPACK_IMPORTED_MODULE_2___default().translator.trans("xypp-pay-to-read.forum.error.internal_err"));
      flarum_forum_app__WEBPACK_IMPORTED_MODULE_2___default().modal.close();
    });
  };
  _proto.pay = function pay(event) {
    var _this3 = this;
    this.loading = true;
    flarum_forum_app__WEBPACK_IMPORTED_MODULE_2___default().request({
      url: flarum_forum_app__WEBPACK_IMPORTED_MODULE_2___default().forum.attribute('apiUrl') + '/pay-to-read/payment/pay',
      body: {
        id: $(event.currentTarget).attr("data-id")
      },
      method: 'POST'
    }).then(function (result) {
      _this3.loading = false;
      if (result.data.attributes.error == "") {
        flarum_forum_app__WEBPACK_IMPORTED_MODULE_2___default().modal.close();
        flarum_common_utils_setRouteWithForcedRefresh__WEBPACK_IMPORTED_MODULE_4___default()(flarum_forum_app__WEBPACK_IMPORTED_MODULE_2___default().history.getCurrent().url);
      } else {
        flarum_forum_app__WEBPACK_IMPORTED_MODULE_2___default().modal.close();
        flarum_forum_app__WEBPACK_IMPORTED_MODULE_2___default().alerts.show({
          type: 'error'
        }, flarum_forum_app__WEBPACK_IMPORTED_MODULE_2___default().translator.trans('xypp-pay-to-read.forum.error.' + result.data.attributes.error, []));
      }
    })["catch"](function (e) {
      console.log(e);
      flarum_forum_app__WEBPACK_IMPORTED_MODULE_2___default().alerts.show({
        type: 'error'
      }, flarum_forum_app__WEBPACK_IMPORTED_MODULE_2___default().translator.trans("xypp-pay-to-read.forum.error.internal_err"));
      flarum_forum_app__WEBPACK_IMPORTED_MODULE_2___default().modal.close();
    });
  };
  return PayModal;
}((flarum_common_components_Modal__WEBPACK_IMPORTED_MODULE_1___default()));


/***/ }),

/***/ "./src/forum/index.js":
/*!****************************!*\
  !*** ./src/forum/index.js ***!
  \****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var flarum_forum_app__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! flarum/forum/app */ "flarum/forum/app");
/* harmony import */ var flarum_forum_app__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(flarum_forum_app__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var flarum_forum_components_CommentPost__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! flarum/forum/components/CommentPost */ "flarum/forum/components/CommentPost");
/* harmony import */ var flarum_forum_components_CommentPost__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(flarum_forum_components_CommentPost__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var flarum_common_extend__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! flarum/common/extend */ "flarum/common/extend");
/* harmony import */ var flarum_common_extend__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(flarum_common_extend__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var flarum_common_components_TextEditor__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! flarum/common/components/TextEditor */ "flarum/common/components/TextEditor");
/* harmony import */ var flarum_common_components_TextEditor__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(flarum_common_components_TextEditor__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var flarum_common_components_TextEditorButton__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! flarum/common/components/TextEditorButton */ "flarum/common/components/TextEditorButton");
/* harmony import */ var flarum_common_components_TextEditorButton__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(flarum_common_components_TextEditorButton__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _ptrBlockHandler__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./ptrBlockHandler */ "./src/forum/ptrBlockHandler.js");






flarum_forum_app__WEBPACK_IMPORTED_MODULE_0___default().initializers.add('xypp/pay-to-read', function () {
  // let payment = app.store.createRecord("payment");
  // payment.save({user_id:1}).then(console.log).catch(console.log);
  (0,flarum_common_extend__WEBPACK_IMPORTED_MODULE_2__.extend)((flarum_forum_components_CommentPost__WEBPACK_IMPORTED_MODULE_1___default().prototype), "content", _ptrBlockHandler__WEBPACK_IMPORTED_MODULE_5__.handlePtrBlock);
  (0,flarum_common_extend__WEBPACK_IMPORTED_MODULE_2__.extend)((flarum_common_components_TextEditor__WEBPACK_IMPORTED_MODULE_3___default().prototype), "toolbarItems", function (items) {
    var _this = this;
    items.add("pay-to-read", m((flarum_common_components_TextEditorButton__WEBPACK_IMPORTED_MODULE_4___default()), {
      onclick: function onclick() {
        _this.attrs.composer.editor.insertAtCursor("[pay ammount=1]" + flarum_forum_app__WEBPACK_IMPORTED_MODULE_0___default().translator.trans("xypp-pay-to-read.forum.editor.help") + "[/pay]");
        var range = _this.attrs.composer.editor.getSelectionRange();
        _this.attrs.composer.editor.moveCursorTo(range[1] - 6);
      },
      icon: "fa fa-comment-dollar"
    }, flarum_forum_app__WEBPACK_IMPORTED_MODULE_0___default().translator.trans("xypp-pay-to-read.forum.editor.label")));
  });
});

/***/ }),

/***/ "./src/forum/ptrBlockHandler.js":
/*!**************************************!*\
  !*** ./src/forum/ptrBlockHandler.js ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   handlePtrBlock: () => (/* binding */ handlePtrBlock)
/* harmony export */ });
/* harmony import */ var flarum_forum_app__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! flarum/forum/app */ "flarum/forum/app");
/* harmony import */ var flarum_forum_app__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(flarum_forum_app__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var flarum_forum_components_LogInModal__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! flarum/forum/components/LogInModal */ "flarum/forum/components/LogInModal");
/* harmony import */ var flarum_forum_components_LogInModal__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(flarum_forum_components_LogInModal__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var flarum_forum_components_DiscussionPage__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! flarum/forum/components/DiscussionPage */ "flarum/forum/components/DiscussionPage");
/* harmony import */ var flarum_forum_components_DiscussionPage__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(flarum_forum_components_DiscussionPage__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _components_PayModal__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./components/PayModal */ "./src/forum/components/PayModal.js");
/* harmony import */ var _common_transUtil__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../common/transUtil */ "./src/common/transUtil.ts");





function handlePtrBlock() {
  if (flarum_forum_app__WEBPACK_IMPORTED_MODULE_0___default().current.matches((flarum_forum_components_DiscussionPage__WEBPACK_IMPORTED_MODULE_2___default()))) {
    $(".ptr-block.ptr-payment-require.ptr-render").each(function (idx, element) {
      $(element).prepend($("<div></div>").addClass("ptr-tip-line").prepend($("<button></button>").text(flarum_forum_app__WEBPACK_IMPORTED_MODULE_0___default().translator.trans("xypp-pay-to-read.forum.payment-req.btn")).addClass("ptr-pay-btn Button Button--primary")).prepend($("<span></span>").addClass("ptr-pay-tip").text(flarum_forum_app__WEBPACK_IMPORTED_MODULE_0___default().translator.trans("xypp-pay-to-read.forum.payment-req.text", [$(element).attr("data-ammount")]).join(""))));
    });
    $(".ptr-block.ptr-notfound.ptr-render").each(function (idx, element) {
      $(element).prepend($("<div></div>").addClass("ptr-tip-line").prepend($("<span></span>").addClass("ptr-pay-tip").text((0,_common_transUtil__WEBPACK_IMPORTED_MODULE_4__.transStr2Str)(flarum_forum_app__WEBPACK_IMPORTED_MODULE_0___default().translator.trans("xypp-pay-to-read.forum.payment-notfound", [$(element).attr("data-id")])))));
    });
    $(".ptr-block.ptr-render").each(function (idx, element) {
      $(element).removeClass("ptr-render");
      $(element).prepend($("<div></div>").addClass("ptr-payment-tip").text(flarum_forum_app__WEBPACK_IMPORTED_MODULE_0___default().translator.trans("xypp-pay-to-read.forum.paymentTip", [$(element).attr("data-ammount")])).append($("<div></div>").addClass("ptr-haspaid-tip").text($(element).attr("data-haspaid-cnt") ? (0,_common_transUtil__WEBPACK_IMPORTED_MODULE_4__.transStr2Str)(flarum_forum_app__WEBPACK_IMPORTED_MODULE_0___default().translator.trans("xypp-pay-to-read.forum.haspaidTip", [$(element).attr("data-haspaid-cnt")])) : flarum_forum_app__WEBPACK_IMPORTED_MODULE_0___default().translator.trans("xypp-pay-to-read.forum.nopaidTip", []))));
    });
    if ((flarum_forum_app__WEBPACK_IMPORTED_MODULE_0___default().session).user) {
      $(".ptr-block .ptr-pay-btn").off("click").on("click", function (e) {
        var box = $(e.currentTarget.parentElement.parentElement);
        flarum_forum_app__WEBPACK_IMPORTED_MODULE_0___default().modal.show(_components_PayModal__WEBPACK_IMPORTED_MODULE_3__["default"], {
          item_id: box.attr("data-id")
        });
      });
    } else {
      $(".ptr-block .ptr-pay-btn").off("click").on("click", function () {
        return flarum_forum_app__WEBPACK_IMPORTED_MODULE_0___default().modal.show((flarum_forum_components_LogInModal__WEBPACK_IMPORTED_MODULE_1___default()));
      });
    }
  }
}

/***/ }),

/***/ "flarum/common/app":
/*!***************************************************!*\
  !*** external "flarum.core.compat['common/app']" ***!
  \***************************************************/
/***/ ((module) => {

"use strict";
module.exports = flarum.core.compat['common/app'];

/***/ }),

/***/ "flarum/common/components/Alert/Alert":
/*!**********************************************************************!*\
  !*** external "flarum.core.compat['common/components/Alert/Alert']" ***!
  \**********************************************************************/
/***/ ((module) => {

"use strict";
module.exports = flarum.core.compat['common/components/Alert/Alert'];

/***/ }),

/***/ "flarum/common/components/Modal":
/*!****************************************************************!*\
  !*** external "flarum.core.compat['common/components/Modal']" ***!
  \****************************************************************/
/***/ ((module) => {

"use strict";
module.exports = flarum.core.compat['common/components/Modal'];

/***/ }),

/***/ "flarum/common/components/TextEditor":
/*!*********************************************************************!*\
  !*** external "flarum.core.compat['common/components/TextEditor']" ***!
  \*********************************************************************/
/***/ ((module) => {

"use strict";
module.exports = flarum.core.compat['common/components/TextEditor'];

/***/ }),

/***/ "flarum/common/components/TextEditorButton":
/*!***************************************************************************!*\
  !*** external "flarum.core.compat['common/components/TextEditorButton']" ***!
  \***************************************************************************/
/***/ ((module) => {

"use strict";
module.exports = flarum.core.compat['common/components/TextEditorButton'];

/***/ }),

/***/ "flarum/common/extend":
/*!******************************************************!*\
  !*** external "flarum.core.compat['common/extend']" ***!
  \******************************************************/
/***/ ((module) => {

"use strict";
module.exports = flarum.core.compat['common/extend'];

/***/ }),

/***/ "flarum/common/utils/setRouteWithForcedRefresh":
/*!*******************************************************************************!*\
  !*** external "flarum.core.compat['common/utils/setRouteWithForcedRefresh']" ***!
  \*******************************************************************************/
/***/ ((module) => {

"use strict";
module.exports = flarum.core.compat['common/utils/setRouteWithForcedRefresh'];

/***/ }),

/***/ "flarum/components/Button":
/*!**********************************************************!*\
  !*** external "flarum.core.compat['components/Button']" ***!
  \**********************************************************/
/***/ ((module) => {

"use strict";
module.exports = flarum.core.compat['components/Button'];

/***/ }),

/***/ "flarum/forum/app":
/*!**************************************************!*\
  !*** external "flarum.core.compat['forum/app']" ***!
  \**************************************************/
/***/ ((module) => {

"use strict";
module.exports = flarum.core.compat['forum/app'];

/***/ }),

/***/ "flarum/forum/components/CommentPost":
/*!*********************************************************************!*\
  !*** external "flarum.core.compat['forum/components/CommentPost']" ***!
  \*********************************************************************/
/***/ ((module) => {

"use strict";
module.exports = flarum.core.compat['forum/components/CommentPost'];

/***/ }),

/***/ "flarum/forum/components/DiscussionPage":
/*!************************************************************************!*\
  !*** external "flarum.core.compat['forum/components/DiscussionPage']" ***!
  \************************************************************************/
/***/ ((module) => {

"use strict";
module.exports = flarum.core.compat['forum/components/DiscussionPage'];

/***/ }),

/***/ "flarum/forum/components/LogInModal":
/*!********************************************************************!*\
  !*** external "flarum.core.compat['forum/components/LogInModal']" ***!
  \********************************************************************/
/***/ ((module) => {

"use strict";
module.exports = flarum.core.compat['forum/components/LogInModal'];

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/inheritsLoose.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/inheritsLoose.js ***!
  \******************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _inheritsLoose)
/* harmony export */ });
/* harmony import */ var _setPrototypeOf_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./setPrototypeOf.js */ "./node_modules/@babel/runtime/helpers/esm/setPrototypeOf.js");

function _inheritsLoose(subClass, superClass) {
  subClass.prototype = Object.create(superClass.prototype);
  subClass.prototype.constructor = subClass;
  (0,_setPrototypeOf_js__WEBPACK_IMPORTED_MODULE_0__["default"])(subClass, superClass);
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/setPrototypeOf.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/setPrototypeOf.js ***!
  \*******************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _setPrototypeOf)
/* harmony export */ });
function _setPrototypeOf(o, p) {
  _setPrototypeOf = Object.setPrototypeOf ? Object.setPrototypeOf.bind() : function _setPrototypeOf(o, p) {
    o.__proto__ = p;
    return o;
  };
  return _setPrototypeOf(o, p);
}

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
/*!******************!*\
  !*** ./forum.ts ***!
  \******************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _src_common__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./src/common */ "./src/common/index.ts");
/* harmony import */ var _src_forum__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./src/forum */ "./src/forum/index.js");


})();

module.exports = __webpack_exports__;
/******/ })()
;
//# sourceMappingURL=forum.js.map