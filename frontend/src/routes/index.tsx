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
    | 'categories.edit'
    | 'members.list'
    | 'members.create'
    | 'members.edit'
    | 'genres.list'
    | 'genres.create'
    | 'genres.edit';

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
    [ 'categories.edit', {
        label: 'Editar categoria',
        path: '/categorias/:id/editar',
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
        label: 'Criar membro',
        path: '/membros-elencos/criar',
        component: CreateCastMember,
        exact: true
    } ],
    [ 'members.edit', {
        label: 'Editar membro',
        path: '/membros-elencos/:id/editar',
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
    [ 'genres.edit', {
        label: 'Criar Gênero',
        path: '/generos/:id/editar',
        component: CreateGenre,
        exact: true
    } ],
]);
