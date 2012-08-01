define
(
    [
        'dojo/_base/declare',
        'dojo/_base/lang',
        'dojo/_base/Deferred',
        'dojox/rpc/Service',
        'dojox/rpc/JsonRPC',
        'dojo/store/JsonRest',
        'dojo/Stateful'
    ],
    function
    (
        declare,
        lang,
        Deferred,
        RpcService,
        JsonRpc,
        JsonRest,
        Stateful
    )
    {
        return declare
        (
            'sijit.UserModule.UserController',
            [Stateful],
            {

                userRestUrl: undefined,

                userApiMap: undefined,

                recoverPasswordForm: undefined,

                registerForm: undefined,

                _store: undefined,

                _userApi: undefined,

                getStore: function(){
                    if ( ! this._store) {
                        this._store = new JsonRest({target: this.userRestUrl});
                    }
                    return this._store;
                },

                getUserApi: function() {
                    if ( ! this.userApi) {
                        this._userApi = new RpcService(this.userApiMap);
                    }
                },

                add: function(user){
                    this.getStore().add(user);
                },


                recoverPassword: function(){
                    this.showRecoverPasswordForm();
                    this._recoverPasswordDeferred = new Deferred();
                    return this._recoverPasswordDeferred;
                },
                showRecoverPasswordForm: function()
                {
                    if (this.recoverPasswordDialog.show == undefined){
                        this.recoverPasswordDialog.use(lang.hitch(this, function(recoverPasswordDialog){
                            this.recoverPasswordDialog = recoverPasswordDialog;
                            this.showRecoverPasswordForm();
                        }));
                        return;
                    }
                    Deferred.when(this.recoverPasswordDialog.show(), lang.hitch(this, function()
                    {
                        var formValues = this.recoverPasswordDialog.getFormValue();
                        this._setStatus({message: 'recovering password', icon: 'spinner'});
                        this.authApi.recoverPassword(formValues['username'], formValues['email']).then(
                            lang.hitch(this, 'recoverPasswordComplete'),
                            lang.hitch(this, 'recoverPasswordError')
                        );
                        this.recoverPasswordDialog.resetForm();
                    }));
                },
                recoverPasswordComplete: function(data)
                {
                    this._setStatus({message: 'recover password complete', icon: 'success', timeout: 5000});
                    this._recoverPasswordDeferred.resolve(true);
                },
                recoverPasswordError: function(error)
                {
                    this.errorService.use(function(errorService){
                        errorService.handle(error);
                    });
                    this._recoverPasswordDeferred.resolve(false);
                },
                register: function(){
                    this.showRegisterForm();
                    this._registerDeferred = new Deferred();
                    return this._registerDeferred;
                },
                showRegisterForm: function()
                {
                    if (this.registerDialog.show == undefined){
                        this.registerDialog.use(lang.hitch(this, function(registerDialog){
                            this.registerDialog = registerDialog;
                            this.showRegisterForm();
                        }));
                        return;
                    }
                    Deferred.when(this.registerDialog.show(), lang.hitch(this, function()
                    {
                        var formValues = this.registerDialog.getFormValue();
                        this._setStatus({message: 'registering new user', icon: 'spinner'});
                        this.authApi.register(formValues['username'], formValues).then(
                            lang.hitch(this, 'registerComplete'),
                            lang.hitch(this, 'registerError')
                        );
                        this.registerDialog.resetForm();
                    }));
                },
                registerComplete: function(data)
                {
                    this._setStatus({message: 'registration complete', icon: 'success', timeout: 5000});
                    this._registerDeferred.resolve(true);
                },
                registerError: function(error)
                {
                    this.errorService.use(function(errorService){
                        errorService.handle(error);
                    });
                    this._registerDeferred.resolve(false);
                },
                refreshPage: function(){
                    if(this.config.pageRefreshTarget){
                        this.pageLoaderService.use(lang.hitch(this, function(pageLoaderService){
                            pageLoaderService.refreshPage(this.config.pageRefreshTarget);
                        }));
                    }
                },
                _setStatus: function(status){
                    this.status.set('status', status);
                }
            }
        );
    }
);