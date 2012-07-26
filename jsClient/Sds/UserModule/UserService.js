define ([
        'dojo/_base/declare'
    ],
    function (
        declare
    ){
        // module:
        //		Sds/UserModule/UserService

        return declare (
            'Sds.UserModule.UserService',
            null,
            {
                // summary:
                //		Provides functions to manipulate user instances

                // id: custom_id
                id: undefined,

                // firstname: string
                firstname: undefined,

                // lastname: string
                lastname: undefined,

                // nickname: string
                nickname: undefined,

                // password: string
                password: undefined,

                // roles: hash
                roles: undefined,

                // username: string
                username: undefined

            }
        );
    }
);


