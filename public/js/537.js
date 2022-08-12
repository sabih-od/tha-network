"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[537],{5418:(e,t,o)=>{o.d(t,{Z:()=>s});var r=o(9038);const s={components:{Link:r.rU},computed:{asset:function(){var e=this;return function(t){return e.$store.getters["Utils/public_asset"](t)}},auth_image:function(){var e,t=null===(e=(0,r.qt)().props.value)||void 0===e?void 0:e.auth_profile_image;return""!==t&&null!==t?t:this.asset("images/small-character.jpg")},profile_link:function(){var e=this;return function(t){return e.$store.getters["Utils/generateProfileLink"](t)}},profile_image:function(){var e=this;return function(t){return""!==t&&t?t:e.asset("images/small-character.jpg")}}},methods:{showSuccessMessage:function(){this.$store.dispatch("Utils/showSuccessMessage")},showErrorMessage:function(){this.$store.dispatch("Utils/showErrorMessages")}}}},3744:(e,t)=>{t.Z=(e,t)=>{const o=e.__vccOpts||e;for(const[e,r]of t)o[e]=r;return o}},6537:(e,t,o)=>{o.r(t),o.d(t,{default:()=>A});var r=o(821),s={class:"loginSection create-profile"},l={class:"loginWrap"},a={class:"row mx-0 no-gutters"},n={class:"col-md-7"},c=["src"],i=["src"],m={class:"col-md-5"},d={class:"contentWrap"},u={href:"#"},p=["src"],f=(0,r.createElementVNode)("h2",null,"Create Profile",-1),h={class:"row"},V={class:"col-md-6"},N={class:"form-group"},v=(0,r.createElementVNode)("label",{for:"fname"},"First Name",-1),E={class:"col-md-6"},g={class:"form-group"},w=(0,r.createElementVNode)("label",{for:"lname"},"Last Name",-1),b={class:"col-md-12"},_={class:"form-group mb-1"},y=(0,r.createElementVNode)("label",{for:"username"},"UserName",-1),x={class:"col-md-6"},U={class:"form-group mb-2"},M=(0,r.createElementVNode)("label",{for:"email"},"Email",-1),T={class:"col-md-6"},k={class:"form-group mb-2"},S=(0,r.createElementVNode)("label",{for:"phone"},"Phone",-1),D={class:"col-md-6"},$={class:"form-group"},P=(0,r.createElementVNode)("label",{for:"password"},"Password",-1),C={class:"col-md-6"},I={class:"form-group"},Z=(0,r.createElementVNode)("label",{for:"cpassword"},"Confirm Password",-1),j=(0,r.createElementVNode)("div",{class:"col-md-12"},[(0,r.createElementVNode)("p",{class:"color-black"},"The password should be at least 8 characters long with (1 upper case letter, 1 number, 1 special character (!@#$%^&*)")],-1),B={class:"col-md-12"},L={class:"form-group mb-2"},q=(0,r.createElementVNode)("label",{for:"securityNo"},"Social Security Number",-1),z=(0,r.createElementVNode)("p",{class:"color-danger"},'All United State citizens/residents are required to enter their social security numbers for Tax purposes. Your information will never be shared or used for any other purposes. If a social is not provided for US citizens/residents payments will not be distributed until your social is provided."',-1),F={type:"submit",class:"themeBtn"};var O=o(9038);o(9680);const W={name:"Register",mixins:[o(5418).Z],props:{errors:Object},data:function(){return{form:(0,O.cI)({first_name:"",last_name:"",username:"",email:"",phone:"",password:"",password_confirmation:"",social_security_number:""})}},methods:{submit:function(){var e=this;this.form.post(this.$route("register"),{replace:!0,onSuccess:function(){e.form.reset()},onFinish:function(){e.$store.dispatch("Utils/showErrorMessages")}})}}};const A=(0,o(3744).Z)(W,[["render",function(e,t,o,O,W,A){return(0,r.openBlock)(),(0,r.createElementBlock)("section",s,[(0,r.createElementVNode)("div",l,[(0,r.createElementVNode)("div",a,[(0,r.createElementVNode)("div",n,[(0,r.createElementVNode)("figure",null,[(0,r.createElementVNode)("img",{src:e.asset("images/loginImg.png"),class:"loginImg",alt:""},null,8,c),(0,r.createElementVNode)("img",{src:e.asset("images/user-logo.png"),class:"login-logo",alt:""},null,8,i)])]),(0,r.createElementVNode)("div",m,[(0,r.createElementVNode)("div",d,[(0,r.createElementVNode)("a",u,[(0,r.createElementVNode)("img",{src:e.asset("images/logo.png"),alt:"logo"},null,8,p)]),f,(0,r.createElementVNode)("form",{onSubmit:t[8]||(t[8]=(0,r.withModifiers)((function(){return A.submit&&A.submit.apply(A,arguments)}),["prevent"]))},[(0,r.createElementVNode)("div",h,[(0,r.createElementVNode)("div",V,[(0,r.createElementVNode)("div",N,[v,(0,r.withDirectives)((0,r.createElementVNode)("input",{type:"text",id:"fname","onUpdate:modelValue":t[0]||(t[0]=function(e){return W.form.first_name=e}),placeholder:"",class:"form-control"},null,512),[[r.vModelText,W.form.first_name]])])]),(0,r.createElementVNode)("div",E,[(0,r.createElementVNode)("div",g,[w,(0,r.withDirectives)((0,r.createElementVNode)("input",{type:"text",id:"lname","onUpdate:modelValue":t[1]||(t[1]=function(e){return W.form.last_name=e}),placeholder:"",class:"form-control"},null,512),[[r.vModelText,W.form.last_name]])])]),(0,r.createElementVNode)("div",b,[(0,r.createElementVNode)("div",_,[y,(0,r.withDirectives)((0,r.createElementVNode)("input",{type:"text",id:"username","onUpdate:modelValue":t[2]||(t[2]=function(e){return W.form.username=e}),placeholder:"",class:"form-control"},null,512),[[r.vModelText,W.form.username]])])]),(0,r.createElementVNode)("div",x,[(0,r.createElementVNode)("div",U,[M,(0,r.withDirectives)((0,r.createElementVNode)("input",{type:"text",id:"email","onUpdate:modelValue":t[3]||(t[3]=function(e){return W.form.email=e}),placeholder:"",class:"form-control"},null,512),[[r.vModelText,W.form.email]])])]),(0,r.createElementVNode)("div",T,[(0,r.createElementVNode)("div",k,[S,(0,r.withDirectives)((0,r.createElementVNode)("input",{type:"text",id:"phone","onUpdate:modelValue":t[4]||(t[4]=function(e){return W.form.phone=e}),placeholder:"",class:"form-control"},null,512),[[r.vModelText,W.form.phone]])])]),(0,r.createElementVNode)("div",D,[(0,r.createElementVNode)("div",$,[P,(0,r.withDirectives)((0,r.createElementVNode)("input",{type:"password",id:"password","onUpdate:modelValue":t[5]||(t[5]=function(e){return W.form.password=e}),placeholder:"",class:"form-control"},null,512),[[r.vModelText,W.form.password]])])]),(0,r.createElementVNode)("div",C,[(0,r.createElementVNode)("div",I,[Z,(0,r.withDirectives)((0,r.createElementVNode)("input",{type:"password",id:"cpassword","onUpdate:modelValue":t[6]||(t[6]=function(e){return W.form.password_confirmation=e}),placeholder:"",class:"form-control"},null,512),[[r.vModelText,W.form.password_confirmation]])])]),j,(0,r.createElementVNode)("div",B,[(0,r.createElementVNode)("div",L,[q,(0,r.withDirectives)((0,r.createElementVNode)("input",{type:"text",id:"securityNo","onUpdate:modelValue":t[7]||(t[7]=function(e){return W.form.social_security_number=e}),placeholder:"",class:"form-control"},null,512),[[r.vModelText,W.form.social_security_number]])]),z])]),(0,r.createElementVNode)("button",F,(0,r.toDisplayString)(W.form.processing?"Please wait...":"NEXT"),1)],32)])])])])])}]])}}]);