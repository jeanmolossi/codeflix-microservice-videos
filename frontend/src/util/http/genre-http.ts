import { HttpResource } from "./http-resource";
import { httpVideo } from "./index";
import { Category } from "./category-http";

export interface Genre {
    id: string;
    name: string;
    is_active: boolean;
    categories_id: string[];
    categories?: Category[];
}

export const genreHttp = new HttpResource<Genre>(
    httpVideo,
    'genres'
);
