import { HttpResource } from "./http-resource";
import { httpVideo } from "./index";

export enum MemberType {
    Actor = 1,
    Director
}

export interface CastMember {
    id: string;
    name: string;
    type: MemberType;
}

export const castMemberHttp = new HttpResource<CastMember>(
    httpVideo,
    'cast_members'
);
