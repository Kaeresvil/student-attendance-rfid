import {createWebHistory, createRouter} from "vue-router";


import Login from '../pages/Login';
import Attendance from '../components/attendance/Form';
import Dashboard from '../components/Dashboard';

import Roles from '../components/Role';
import AddRole from '../components/AddRole';
import EditRole from '../components/EditRole';
import User from '../components/users/users.vue';
import AddUser from '../components/AddUser';
import EditUser from '../components/EditUser';

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
                name: 'role',
                path: '/roles',
                component: Roles
            },
            {
                name: 'addrole',
                path: '/roles/add',
                component: AddRole
            },
            {
                name: 'editrole',
                path: '/roles/edit/:id',
                component: EditRole
            },
            {
                name: 'user',
                path: '/users',
                component: User
            },
            {
                name: 'adduser',
                path: '/user/add',
                component: AddUser
            },
            {
                name: 'edituser',
                path: '/users/edit/:id',
                component: EditUser
            },
        ]
    },
   
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;