import { RouteProps as DOMRouteProps } from 'react-router-dom';
import { Dashboard } from "../pages/Dashboard";
import { ListCategories } from "../pages/category/ListCategories";
import { CreateCategory } from "../pages/category/CreateCategory";
import { ListCastMembers } from "../pages/cast-members/ListCastMembers";
import { ListGenres } from "../pages/genre/ListGenres";

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
        label: 'Listar categorias',
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
        label: 'Listagem de membros de elencos',
        path: '/membros-elencos',
        component: ListCastMembers,
        exact: true
    } ],
    [ 'members.create', {
        label: 'Criar categorias',
        path: '/membros-elencos/criar',
        component: () => <h1>Criar membro</h1>,
        exact: true
    } ],
    [ 'genres.list', {
        label: 'Listagem de generos',
        path: '/generos',
        component: ListGenres,
        exact: true
    } ],
    [ 'genres.create', {
        label: 'Criar Genero',
        path: '/generos/criar',
        component: () => <h1>Criar membro</h1>,
        exact: true
    } ],
]);
