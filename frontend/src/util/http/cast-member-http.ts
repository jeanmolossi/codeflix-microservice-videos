import {HttpResource} from "./http-resource";
import {httpVideo} from "./index";
import {CastMember} from "../../core/models";

export enum MemberType {
    Actor = 1,
    Director
}


export const castMemberHttp = new HttpResource<CastMember>(
    httpVideo,
    'cast_members'
);
