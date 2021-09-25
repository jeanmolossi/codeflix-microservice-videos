import {httpVideo} from "./index";
import {HttpResource} from "./http-resource";
import {Category} from "../../core/models";


export const categoryHttp = new HttpResource<Category, string>(httpVideo, 'categories')

