(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-user-pages-ruler"],{1739:function(t,n,r){"use strict";r.d(n,"b",(function(){return a})),r.d(n,"c",(function(){return o})),r.d(n,"a",(function(){return e}));var e={uNavbar:r("115d").default,uIcon:r("6aa6").default},a=function(){var t=this,n=t.$createElement,r=t._self._c||n;return r("v-uni-view",{staticClass:"ruler-root"},[r("u-navbar",{attrs:{safeAreaInsetTop:!0,title:t.title,placeholder:!0,border:!1,bgColor:t.bgColor,fixed:!1},on:{leftClick:function(n){arguments[0]=n=t.$handleEvent(n),t.backClick.apply(void 0,arguments)}}},[t.myfrom?r("v-uni-view",{staticClass:"u-nav-left",attrs:{slot:"left"},slot:"left"},[r("u-icon",{attrs:{name:"arrow-left",size:"20",color:"#fff"}})],1):t._e()],1),r("v-uni-view",{staticClass:"content"},[r("jyfParser",{attrs:{html:t.content}})],1)],1)},o=[]},"6f76":function(t,n,r){"use strict";r.r(n);var e=r("9f10"),a=r.n(e);for(var o in e)"default"!==o&&function(t){r.d(n,t,(function(){return e[t]}))}(o);n["default"]=a.a},"82f6":function(t,n,r){"use strict";var e=r("c8fb"),a=r.n(e);a.a},"9f10":function(t,n,r){"use strict";var e=r("4ea4");Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var a=e(r("ad02")),o={components:{jyfParser:a.default},data:function(){return{title:"",bgColor:"#000",content:null,id:null,myfrom:!0}},onLoad:function(t){this.id=t.id,t.myfrom&&(this.myfrom=!1)},onShow:function(){var t=this;uni.showLoading({title:"加载中..."}),this.$Request.post(this.$api.common.single,{id:this.id}).then((function(n){0!=n.code&&1!=n.code||uni.hideLoading(),t.content=n.data.content,t.title=n.data.title}))},methods:{backClick:function(){uni.navigateBack({})}}};n.default=o},c8fb:function(t,n,r){var e=r("f884");"string"===typeof e&&(e=[[t.i,e,""]]),e.locals&&(t.exports=e.locals);var a=r("4f06").default;a("10acc451",e,!0,{sourceMap:!1,shadowMode:!1})},f884:function(t,n,r){var e=r("24fb");n=e(!1),n.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.ruler-root[data-v-6a70b23a]{width:100%;min-height:100vh;background-color:#000}.ruler-root .content[data-v-6a70b23a]{width:100%;color:#fff;padding:%?20?% %?25?%}',""]),t.exports=n},f98e:function(t,n,r){"use strict";r.r(n);var e=r("1739"),a=r("6f76");for(var o in a)"default"!==o&&function(t){r.d(n,t,(function(){return a[t]}))}(o);r("82f6");var i,u=r("f0c5"),c=Object(u["a"])(a["default"],e["b"],e["c"],!1,null,"6a70b23a",null,!1,e["a"],i);n["default"]=c.exports}}]);