define(
    [],
    function(){
        return {
            serviceManager: {
                userController: {
                    moduleName: 'Sds/UserModule/UserController',
                    values: {
                        userRestUrl: '/user/rest/'
//                        userApiMap: '../../../../auth'
                    },
                    refObjects: {
//                        status: 'status',
//                        errorService: 'errorController',
                        recoverPasswordForm: 'Sds/UserModule/RecoverPasswordFormDialog',
                        registerForm: 'Sds/UserModule/RegisterFormDialog'
                    }
                },
                user: {
                    moduleName: 'Sds/UserModule/Model/User'
                }
            }
        }
    }
);


