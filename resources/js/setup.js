(function(factory){
    // Establish the root object, `window` (`self`) in the browser, or `global` on the server.
    // We use `self` instead of `window` for `WebWorker` support.
    var root = (typeof self === 'object' && self.self === self && self) ||
        (typeof global === 'object' && global.global === global && global);
    root.wp2sv=root.wp2sv||{};
    root.wp2sv.setup=factory(Vue,_,jQuery);

})
(function(Vue,_,$){

    let module={
        init:function(){
            this.registerComponents();
            if($('#wp2sv-setup').length) {
                this.vm = new Vue({
                    el: '#wp2sv-setup',
                });
            }
        },
        registerComponents:function(){
            Vue.component('wp2sv-route',require('./page/route'));
            Vue.component('wp2sv-setup',require('./page/setup'));
            Vue.component('wp2sv-app-passwords',require('./page/app-passwords'));
            Vue.component('wp2sv-status',require('./components/status'));
            Vue.component('wp2sv-settings',require('./components/settings'));
            Vue.component('wp2sv-clock',require('./components/clock'));
            Vue.component('wp2sv-enroll-email',require('./components/enroll-email'));
            Vue.component('wp2sv-enroll-app',require('./components/enroll-app'));
            Vue.component('wp2sv-enroll-welcome',require('./components/enroll-welcome'));
            Vue.component('wp2sv-start',require('./components/start'));

            Vue.component('authenticator', require('./components/authenticator'));

            Vue.component('backup-codes',require('./components/backup-codes'));

            Vue.component('wp2sv-emails',require('./components/emails'));
        }
    };
    module.init();
    return module;
});
