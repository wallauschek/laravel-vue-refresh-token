const cookies = require('vue-cookies');
const perfil = {};

perfil.install = function (vue, options) {
    const $perfil = {};

    $perfil.getPerfil = () => {
        return cookies.get('refresh_token');
    };

    $perfil.getPerfil = (user) => {

        const data = Object.assign(user);

        axios.post('/api/user/perfil', data).then((res) => {
            console.log('consulta perfil');
            cookies.set('perfil', res.data.perfil);
        });
    };

    const token = $perfil.getPerfil();

    vue.prototype.$perfil = $perfil;
};

export default perfil;
