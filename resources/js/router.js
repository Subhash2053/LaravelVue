import Vue from 'vue';
import VueRouter from 'vue-router';
import LoginComponent from './components/LoginComponent';
import AdminComponent from './components/AdminComponent';
import WebsiteComponent from './components/WebsiteComponent';
import RoleComponent from './components/RoleComponent';

const foo = {template:"<v-alert type='error'>I'm foo</v-alert>"}
const bar = {template:"<v-alert type='error'>I'm bar</v-alert>"}
const user = {template:"<v-alert type='info'>I'm {{ $route.params.name }}</v-alert>"}


Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        component:WebsiteComponent,
        name:'website',

    },
    {
        path: '/login',
        component:LoginComponent,
        name:'Login',
         beforeEnter: (to, from, next) => {
            if(localStorage.getItem('token'))
            {
                next('/admin');

            }else{
                next();
            }
          }

    },
    {
        path: '/admin',
        component:AdminComponent,
        name:'Admin',
        beforeEnter: (to, from, next) => {
            if(localStorage.getItem('token'))
            {
                next();

            }else{
                next('/login');
            }
          },
          children:[
            {
                path: 'roles',
                component:RoleComponent,
                name:'Roles',
            }
          ]
    }
    ,
    {
        path: '/user/:name',
        component:user
    }

]

export default new VueRouter({routes})
