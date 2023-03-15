import {createWebHistory, createRouter} from "vue-router";


import Login from '../pages/Login';
import Attendance from '../components/attendance/Form';
import Dashboard from '../components/Dashboard';


export const routes = [
    {
        name: 'login',
        path: '/',
        component: Login
    },
   
    {
        path: '/',
        redirect:'/posts',
        component: Dashboard,
        children:[
            {
                name: 'home',
                path: '/home',
                component: ()=> import("../components/home/home.vue")
            },
            {
                name: 'attendance',
                path: '/attendance',
                component: Attendance
            },
            {
                name: 'students',
                path: '/students',
                component: ()=> import("../components/students/students.vue")
            },
            {
                name: 'create-students',
                path: '/create/students',
                component: ()=> import("../components/students/Form.vue")
            },
            {
                name: 'edit-students',
                path: '/edit/students/:id',
                component: ()=> import("../components/students/Form.vue"),
                props:true
            },
            {
                name: 'events',
                path: '/events',
                component: ()=> import("../components/events/events.vue")
            },
            {
                name: 'reports',
                path: '/reports',
                component: ()=> import("../components/reports/reports.vue")
            },
            {
                name: 'roles',
                path: '/roles',
                component: ()=> import("../components/roles/roles.vue")
            },
            {
                name: 'grades',
                path: '/grades',
                component: ()=> import("../components/grades/grades.vue")
            },
            {
                name: 'create-grades',
                path: '/create/grades',
                component: ()=> import("../components/grades/Form.vue")
            },
            {
                name: 'edit-grades',
                path: '/edit/grades/:id',
                component: ()=> import("../components/grades/Form.vue"),
                props:true
            },
            {
                name: 'create-sections',
                path: '/create/sections',
                component: ()=> import("../components/sections/Form.vue")
            },
            {
                name: 'edit-sections',
                path: '/edit/sections/:id',
                component: ()=> import("../components/sections/Form.vue"),
            },
            {
                name: 'sections',
                path: '/sections',
                component: ()=> import("../components/sections/sections.vue")
            },
            {
                name: 'create-roles',
                path: '/create/roles',
                component: ()=> import("../components/roles/Form.vue")
            },
            {
                name: 'edit-roles',
                path: '/edit/roles/:id',
                component: ()=> import("../components/roles/Form.vue"),
            },
            {
                name: 'user',
                path: '/users',
                component: ()=> import("../components/users/users.vue")
            },
            {
                name: 'create-users',
                path: '/create/users',
                component: ()=> import("../components/users/Form.vue")
            },
            {
                name: 'edit-users',
                path: '/edit/users/:id',
                component: ()=> import("../components/users/Form.vue"),
            },
           
        ]
    },
   
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;