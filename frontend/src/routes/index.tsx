import { RouteProps as DOMRouteProps } from 'react-router-dom';
import { Dashboard } from "../pages/Dashboard";
import { ListCategories } from "../pages/category/ListCategories";
import { CreateCategory } from "../pages/category/CreateCategory";
import { ListCastMembers } from "../pages/cast-members/ListCastMembers";
import { ListGenres } from "../pages/genre/ListGenres";
import { CreateCastMember } from "../pages/cast-members/CreateCastMember";
import { CreateGenre } from "../pages/genre/CreateGenre";

export type RouteNames = 'dashboard'
    | 'categories.list'
    | 'categories.create'
    | 'members.list'
    | 'members.create'
    | 'genres.list'
    | 'genres.create';

export interface RouteProps extends DOMRouteProps {
    label: string
}

export const routes: Map<RouteNames, RouteProps> = new Map([
    [ 'dashboard', {
        label: 'Dashboard',
        path: '/',
        component: Dashboard,
        exact: true
    } ],
    [ 'categories.list', {
        label: 'Categorias',
        path: '/categorias',
        component: ListCategories,
        exact: true
    } ],
    [ 'categories.create', {
        label: 'Criar categorias',
        path: '/categorias/criar',
        component: CreateCategory,
        exact: true
    } ],
    [ 'members.list', {
        label: 'Membros de elencos',
        path: '/membros-elencos',
        component: ListCastMembers,
        exact: true
    } ],
    [ 'members.create', {
        label: 'Criar categorias',
        path: '/membros-elencos/criar',
        component: CreateCastMember,
        exact: true
    } ],
    [ 'genres.list', {
        label: 'Gêneros',
        path: '/generos',
        component: ListGenres,
        exact: true
    } ],
    [ 'genres.create', {
        label: 'Criar Gênero',
        path: '/generos/criar',
        component: CreateGenre,
        exact: true
    } ],
]);
