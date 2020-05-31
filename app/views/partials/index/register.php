    <template id="Register">
        <section class="page-component">
            <div v-if="showheader" class="bg-light p-3 mb-3">
                <div class="container">
                    <div class="row ">
                        <div  class="col-md-6 comp-grid" :class="setGridSize">
                            <h3 class="record-title">User registration</h3>
                        </div>
                        <div  class="col-md-6 comp-grid" :class="setGridSize">
                            <div class="">
                                <div class="text-right">
                                    Already have an account?  <router-link class="btn btn-primary" to="/"> Login </router-link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div  class="pb-2 mb-3 border-bottom">
                <div class="container">
                    <div class="row ">
                        <div  class="col-md-7 comp-grid" :class="setGridSize">
                            <div  class=" animated fadeIn">
                                <form enctype="multipart/form-data" @submit="save" class="form form-default" action="" method="post">
                                    <div class="form-group " :class="{'has-error' : errors.has('Name')}">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="Name">Name <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="">
                                                    <input v-model="data.Name"
                                                    v-validate="{required:true}"
                                                    data-vv-as="Name"
                                                    class="form-control "
                                                    type="text"
                                                    name="Name"
                                                    placeholder="Enter Name"
                                                    />
                                                    <small v-show="errors.has('Name')" class="form-text text-danger">
                                                        {{ errors.first('Name') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group " :class="{'has-error' : errors.has('Surname')}">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="Surname">Surname <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="">
                                                    <input v-model="data.Surname"
                                                    v-validate="{required:true}"
                                                    data-vv-as="Surname"
                                                    class="form-control "
                                                    type="text"
                                                    name="Surname"
                                                    placeholder="Enter Surname"
                                                    />
                                                    <small v-show="errors.has('Surname')" class="form-text text-danger">
                                                        {{ errors.first('Surname') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group " :class="{'has-error' : errors.has('UserName')}">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="UserName">Username <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="">
                                                    <input v-model="data.UserName"
                                                    v-validate="{required:true}"
                                                    data-vv-as="Username"
                                                    class="form-control "
                                                    type="text"
                                                    name="UserName"
                                                    placeholder="Enter Username"
                                                    />
                                                    <small v-show="errors.has('UserName')" class="form-text text-danger">
                                                        {{ errors.first('UserName') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group " :class="{'has-error' : errors.has('Email')}">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="Email">Email <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="">
                                                    <input v-model="data.Email"
                                                    v-validate="{required:true,  email:true}"
                                                    data-vv-as="Email"
                                                    class="form-control "
                                                    type="email"
                                                    name="Email"
                                                    placeholder="Enter Email"
                                                    />
                                                    <small v-show="errors.has('Email')" class="form-text text-danger">
                                                        {{ errors.first('Email') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group " :class="{'has-error' : errors.has('PasswordHash')}">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="PasswordHash">Passwordhash <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="">
                                                    <input v-model="data.PasswordHash"
                                                    v-validate="{required:true}"
                                                    data-vv-as="Passwordhash"
                                                    class="form-control "
                                                    type="password"
                                                    name="PasswordHash"
                                                    placeholder="Enter Passwordhash"
                                                    />
                                                    <small v-show="errors.has('PasswordHash')" class="form-text text-danger">
                                                        {{ errors.first('PasswordHash') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group " :class="{'has-error' : errors.has('confirm_password')}">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="">
                                                    <input
                                                    v-model="data.confirm_password"
                                                    v-validate="{ required:true, confirmed:'PasswordHash' }"
                                                    data-vv-as="Confirm Password"
                                                    class="form-control "
                                                    type="password"
                                                    name="confirm_password"
                                                    placeholder="Confirm Password"
                                                    />
                                                    <small v-show="errors.has('confirm_password')" class="form-text text-danger">{{ errors.first('confirm_password') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group " :class="{'has-error' : errors.has('Role')}">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="Role">Role <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="">
                                                    <input v-model="data.Role"
                                                    v-validate="{required:true}"
                                                    data-vv-as="Role"
                                                    class="form-control "
                                                    type="text"
                                                    name="Role"
                                                    placeholder="Enter Role"
                                                    />
                                                    <small v-show="errors.has('Role')" class="form-text text-danger">
                                                        {{ errors.first('Role') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group " :class="{'has-error' : errors.has('AccountStatus')}">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="AccountStatus">Accountstatus <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="">
                                                    <input v-model="data.AccountStatus"
                                                    v-validate="{required:true,  numeric:true}"
                                                    data-vv-as="Accountstatus"
                                                    class="form-control "
                                                    type="number"
                                                    name="AccountStatus"
                                                    placeholder="Enter Accountstatus"
                                                    step="1" 
                                                    />
                                                    <small v-show="errors.has('AccountStatus')" class="form-text text-danger">
                                                        {{ errors.first('AccountStatus') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group " :class="{'has-error' : errors.has('Created')}">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="Created">Created <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="">
                                                    <input v-model="data.Created"
                                                    v-validate="{required:true}"
                                                    data-vv-as="Created"
                                                    class="form-control "
                                                    type="text"
                                                    name="Created"
                                                    placeholder="Enter Created"
                                                    />
                                                    <small v-show="errors.has('Created')" class="form-text text-danger">
                                                        {{ errors.first('Created') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group " :class="{'has-error' : errors.has('Modified')}">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="Modified">Modified <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="">
                                                    <input v-model="data.Modified"
                                                    v-validate="{required:true}"
                                                    data-vv-as="Modified"
                                                    class="form-control "
                                                    type="text"
                                                    name="Modified"
                                                    placeholder="Enter Modified"
                                                    />
                                                    <small v-show="errors.has('Modified')" class="form-text text-danger">
                                                        {{ errors.first('Modified') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="text-center">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="load-indicator"><clip-loader :loading="saving" color="#fff" size="14px"></clip-loader> </i>
                                            {{submitbuttontext}}
                                            <i class="fa fa-send"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </template>
    <script>
	var RegisterComponent = Vue.component('Register', {
		template : '#Register',
		mixins: [AddPageMixin],
		props:{
			pagename : {
				type : String,
				default : 'usertbl',
			},
			routename : {
				type : String,
				default : 'usertbluserregister',
			},
			apipath : {
				type : String,
				default : 'index/register',
			},
		},
		data : function() {
			return {
				id : {
					type : String,
					default : '',
				},
				data : {
					Name: '',Surname: '',UserName: '',Email: '',PasswordHash: '',confirm_password: '',Role: '',AccountStatus: '',Created: '',Modified: '',
				},
			}
		},
		computed: {
			pageTitle: function(){
				return 'Add New Usertbl';
			},
		},
		methods: {
			actionAfterSave : function(response){
				this.$root.$emit('requestCompleted' , this.msgaftersave);
				window.location = response.body;
			},
			resetForm : function(){
				this.data = {Name: '',Surname: '',UserName: '',Email: '',PasswordHash: '',confirm_password: '',Role: '',AccountStatus: '',Created: '',Modified: '',};
				this.$validator.reset();
			},
		},
		mounted : function() {
		},
	});
	</script>
