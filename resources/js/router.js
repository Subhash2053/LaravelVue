import Vue from 'vue';
import VueRouter from 'vue-router';
import LoginComponent from './components/LoginComponent';
import AdminComponent from './components/AdminComponent';
import WebsiteComponent from './components/WebsiteComponent';
import RoleComponent from './components/RoleComponent';
import UserComponent from './components/UserComponent';

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
          children:[
            {
                path: 'roles',
                component:RoleComponent,
                name:'Roles',
            },
            {
                path: 'users',
                component:UserComponent,
                name:'Users',
            }
          ],
          beforeEnter: (to, from, next) => {
              axios.get('api/verify').then(res=>next())
              .catch(err=>next('/login'))
          }
    }
    ,
    {
        path: '/user/:name',
        component:user
    }

]

const router = new VueRouter({routes})
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token') || null
    window.axios.defaults.headers.common['Authorization'] = 'Bearer '+token;
    next();
})
export default router
