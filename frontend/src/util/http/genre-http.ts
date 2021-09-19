import { HttpResource } from "./http-resource";
import { httpVideo } from "./index";

export interface Genre {
    id: string;
    name: string;
    is_active: boolean;
    categories_id: string[];
}

export const genreHttp = new HttpResource<Genre>(
    httpVideo,
    'genres'
);
