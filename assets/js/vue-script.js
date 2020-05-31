var bus = new Vue({});
var routes = [
  
	{ path: '/', name: 'home' , component: HomeComponent },

	{ path: '/usertbl', name: 'usertbllist', component: UsertblListComponent },
	{ path: '/usertbl/(index|list)', name: 'usertbllist' , component: UsertblListComponent },
	{ path: '/usertbl/(index|list)/:fieldname/:fieldvalue', name: 'usertbllist' , component: UsertblListComponent , props: true },
	{ path: '/usertbl/view/:id', name: 'usertblview' , component: UsertblViewComponent , props: true},
	{ path: '/usertbl/view/:fieldname/:fieldvalue', name: 'usertblview' , component: UsertblViewComponent , props: true },
	{ path: '/usertbl/add', name: 'usertbladd' , component: UsertblAddComponent },
	{ path: '/usertbl/edit/:id' , name: 'usertbledit' , component: UsertblEditComponent , props: true},
	{ path: '/usertbl/edit', name: 'usertbledit' , component: UsertblEditComponent , props: true},

	{ path: '/goaltbl', name: 'goaltbllist', component: GoaltblListComponent },
	{ path: '/goaltbl/(index|list)', name: 'goaltbllist' , component: GoaltblListComponent },
	{ path: '/goaltbl/(index|list)/:fieldname/:fieldvalue', name: 'goaltbllist' , component: GoaltblListComponent , props: true },
	{ path: '/goaltbl/view/:id', name: 'goaltblview' , component: GoaltblViewComponent , props: true},
	{ path: '/goaltbl/view/:fieldname/:fieldvalue', name: 'goaltblview' , component: GoaltblViewComponent , props: true },
	{ path: '/goaltbl/add', name: 'goaltbladd' , component: GoaltblAddComponent },
	{ path: '/goaltbl/edit/:id' , name: 'goaltbledit' , component: GoaltblEditComponent , props: true},
	{ path: '/goaltbl/edit', name: 'goaltbledit' , component: GoaltblEditComponent , props: true},

	{ path: '/indicatortbl', name: 'indicatortbllist', component: IndicatortblListComponent },
	{ path: '/indicatortbl/(index|list)', name: 'indicatortbllist' , component: IndicatortblListComponent },
	{ path: '/indicatortbl/(index|list)/:fieldname/:fieldvalue', name: 'indicatortbllist' , component: IndicatortblListComponent , props: true },
	{ path: '/indicatortbl/view/:id', name: 'indicatortblview' , component: IndicatortblViewComponent , props: true},
	{ path: '/indicatortbl/view/:fieldname/:fieldvalue', name: 'indicatortblview' , component: IndicatortblViewComponent , props: true },
	{ path: '/indicatortbl/add', name: 'indicatortbladd' , component: IndicatortblAddComponent },
	{ path: '/indicatortbl/edit/:id' , name: 'indicatortbledit' , component: IndicatortblEditComponent , props: true},
	{ path: '/indicatortbl/edit', name: 'indicatortbledit' , component: IndicatortblEditComponent , props: true},
        
//        { path: '/updatedata', name: 'updatedatalist', component: UpdateDataComponent },
//	{ path: '/updatedata/(index|list)', name: 'updatedatalist' , component: UpdateDataComponent },
//	{ path: '/updatedata/(index|list)/:fieldname/:fieldvalue', name: 'updatedatalist' , component: UpdateDataComponent , props: true },

	{ path: '/home', name: 'home' , component: HomeComponent },
        { path: '/home/goal/:id', name: 'homeGoalView' , component: GoalComponent },
	{ path: '*', name: 'pagenotfound' , component: ComponentNotFound }
];

var router = new VueRouter({
	routes:routes,
	linkExactActiveClass:'active',
	linkActiveClass:'active',
	//mode:'history'
});
router.beforeEach(function(to, from, next) {
	document.body.className = to.name;
	
	next();

});
var config = {
	errorBagName: 'errors', // change if property conflicts
	fieldsBagName: 'fields', 
	delay: 0, 
	locale: '', 
	dictionary: null, 
	strict: true, 
	classes: false, 
	classNames: {
		touched: 'touched', // the control has been blurred
		untouched: 'untouched', // the control hasn't been blurred
		valid: 'valid', // model is valid
		invalid: 'invalid', // model is invalid
		pristine: 'pristine', // control has not been interacted with
		dirty: 'dirty' // control has been interacted with
	},
	events: 'input|blur',
	inject: true,
	validity: false,
	aria: true,
	i18n: null, // the vue-i18n plugin instance,
	i18nRootKey: 'validations', // the nested key under which the validation messsages will be located
};

Vue.use(VeeValidate,config);
Vue.http.interceptors.push(function(request, next) {
	next(function(response){
		if(response.status == 401 ) {
			if(this.$route.name != 'index'){
				window.location = "/"
				//this.$router.push('index');
			}
		}
		else if(response.status == 403 ) {
			alert(response.statusText);
			window.location = 'errors/forbidden';
		}
	});
});
Vue.mixin({
	data: function() {
		return {
			get ActiveUser() {
				return ActiveUser
			}
		}
	},
	methods: {
		SetPageTitle: function(title, pagename){
			document.title = title;
		},
		setPhotoUrl: function(src, width,height){
			var newSrc = 'helpers/timthumb.php?src=' + src;
			if(width){
				newSrc = newSrc + '&w=' + width
			}
			if(height){
				newSrc = newSrc + '&h=' + height	
			}
			return  newSrc;
		},
		exportPage: function(pagehtml , reporttitle){
			var form = document.getElementById("exportform");
			document.getElementById("exportformdata").value = pagehtml ;
			document.getElementById("exportformtitle").value = reporttitle ;
			form.submit();
		}
	}
});

var app = new Vue({
	el: '#app',
	router: router,
	data:{
		showPageError : false,
		pageErrorMsg : '',
		pageErrorStatus : '',
		modalComponentName: '',
		modalComponentProps: '',
		popoverTarget : '',
		showModalView : false,
		showFlash : false,
		flashMsg : '',
	},
	watch : {
		'$route': function(){
			this.pageErrorMsg = '' ;
			this.showPageError = false ;
		},
	},
	mounted : function(){
		this.$on('requestCompleted' ,  function (msg) {
			this.showModal = false;
			if(msg){
				this.showFlash = 3 ;
				this.flashMsg = msg ;
			}
			bus.$emit('refresh');
		});
		this.$on('requestError' ,  function (response) {
			this.pageErrorMsg = response.bodyText ;
			this.pageErrorStatus = response.statusText ;
			this.showPageError = true ;
		})
		
		this.$on('showPageModal' ,  function (props) {
			if(props.page){
				this.modalComponentName = props.page;
				delete props.page;
				props.resetgrid = true; // reset columns so that page components will fit well
				this.modalComponentProps = props;
				this.showModalView = true;
			}
			else{
				console.error("No Page Defined")
			}
		})
		
		this.$on('showPopOver' ,  function (props) {
			if(props.page && props.target){
				this.modalComponentName = props.page;
				this.popoverTarget = props.target;
				delete props.page;
				delete props.target;
				props.resetgrid=true;
				this.modalComponentProps = props;
			}
			else{
				console.error("No Page or Target  Defined")
			}
		})
		
		this.$on('showListModal' ,  function (arr) {
			this.modalComponentName = arr[0];
			this.modalFieldName = arr[1];
			this.modalFieldValue = arr[2];
			this.showModalList = true;
		})
	}
});


Vue.filter('toUSD', function (value) {
	return '$'+ value;
});
Vue.filter('upper', function (value) {
	return value.toUpperCase();
});
Vue.filter('lower', function (value) {
	return value.toLowerCase();
});
Vue.filter('proper', function (value) {
	return value.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
});
Vue.filter('truncate', function (text, length, suffix) {
	return text.substring(0, length) + suffix;
});
Vue.filter('toFixed', function (price, limit) {
	return price.toFixed(limit);
});
Vue.filter('makeRead', function (str) {
	return str.replace(/[-_]/g, " ");
});
Vue.filter('humanDate', function (datestr) {
	var timeStamp = new Date(datestr);
	return timeStamp.toDateString();
});
Vue.filter('humanTime', function (datestr) {
	var timeStamp = new Date(datestr);
	return timeStamp.toLocaleTimeString();
});

Vue.filter('toLocaleString', function (val) {
	return val.toLocaleString();
});
