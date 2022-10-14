"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_App_js"],{

/***/ "./resources/js/App.js":
/*!*****************************!*\
  !*** ./resources/js/App.js ***!
  \*****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var v_viewer__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! v-viewer */ "./node_modules/v-viewer/dist/v-viewer.js");
/* harmony import */ var v_viewer__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(v_viewer__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var viewerjs_dist_viewer_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! viewerjs/dist/viewer.css */ "./node_modules/viewerjs/dist/viewer.css");
/* harmony import */ var _plugins_vuetify__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../plugins/vuetify */ "./resources/plugins/vuetify.js");
/* harmony import */ var _router__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./router */ "./resources/js/router/index.js");
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./store */ "./resources/js/store/index.js");



__webpack_require__(/*! ./bootstrap */ "./resources/js/bootstrap.js");




window.Vue = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm.js")["default"];
Vue.component("app-container", __webpack_require__(/*! ./components/App */ "./resources/js/components/App.vue")["default"]);
Vue.component("viewer", (v_viewer__WEBPACK_IMPORTED_MODULE_0___default()));
Vue.use((v_viewer__WEBPACK_IMPORTED_MODULE_0___default()));
var app = new Vue({
  el: "#app",
  vuetify: _plugins_vuetify__WEBPACK_IMPORTED_MODULE_2__["default"],
  router: _router__WEBPACK_IMPORTED_MODULE_3__["default"],
  store: _store__WEBPACK_IMPORTED_MODULE_4__["default"]
});

/***/ })

}]);