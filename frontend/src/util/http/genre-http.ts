import {HttpResource} from "./http-resource";
import {httpVideo} from "./index";
import {Genre} from "../../core/models";


export const genreHttp = new HttpResource<Genre>(
    httpVideo,
    'genres'
);
