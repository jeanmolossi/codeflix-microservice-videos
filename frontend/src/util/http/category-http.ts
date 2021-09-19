import { httpVideo } from "./index";
import { HttpResource } from "./http-resource";

export interface Category {
    id: string;
    name: string;
    description: string;
    is_active: boolean;
}

export const categoryHttp = new HttpResource<Category, string>(httpVideo, 'categories')

