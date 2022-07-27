import {createWebHistory, createRouter} from "vue-router";


import Login from '../pages/Login';
import Posts from '../components/Post';
import EditPost from '../components/EditPost';
import AddPost from '../components/AddPost';
import Roles from '../components/Role';
import AddRole from '../components/AddRole';
import EditRole from '../components/EditRole';
import User from '../components/Users';
import AddUser from '../components/AddUser';
import EditUser from '../components/EditUser';

export const routes = [
    {
        name: 'login',
        path: '/',
        component: Login
    },
    {
        name: 'posts',
        path: '/posts',
        component: Posts
    },
    {
        name: 'addpost',
        path: '/posts/add',
        component: AddPost
    },
    {
        name: 'editpost',
        path: '/posts/edit/:id',
        component: EditPost
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
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;