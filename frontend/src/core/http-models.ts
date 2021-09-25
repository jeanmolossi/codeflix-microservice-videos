import {AxiosResponse} from "axios";

export interface Links {
    first: string
    last: string
    next: string | null
    prev: string | null
}

export interface MetaLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface Meta {
    current_page: number;
    from: number;
    last_page: number;
    links: Array<MetaLink>;
    path: string;
    per_page: number;
    to: number;
    total: number;
}

export interface WithTimestamps {
    readonly created_at: string;
    readonly updated_at: string;
    readonly deleted_at: string | null;
}

export type DataResource<T> = { data: T, links: Links, meta: Meta };
export type DataResponse<T> = AxiosResponse<DataResource<T>>;
