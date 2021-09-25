import {MemberType} from "../util/http/cast-member-http";
import {WithTimestamps} from "./http-models";

export interface Category extends WithTimestamps {
    readonly id: string;
    name: string;
    description: string;
    is_active: boolean;
}

export interface Genre extends WithTimestamps {
    readonly id: string;
    name: string;
    is_active: boolean;
    categories_id: string[];
    categories?: Category[];
}

export interface CastMember extends WithTimestamps {
    readonly id: string;
    name: string;
    type: MemberType;
}
