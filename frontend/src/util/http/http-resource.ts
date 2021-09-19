import { AxiosInstance, AxiosResponse } from 'axios';

type DataResource<T> = { data: T };
type DataResponse<T> = AxiosResponse<DataResource<T>>;

export class HttpResource<T = any, ID = string> {

    constructor(protected http: AxiosInstance, protected resource: string) {
    }

    list(): Promise<DataResponse<T[]>> {
        return this.http.get<DataResource<T[]>>(this.resource)
    }

    get(id: ID): Promise<DataResponse<T>> {
        return this.http.get<DataResource<T>>(`${ this.resource }/${ id }`)
    }

    create<D = any>(data: D): Promise<DataResponse<T>> {
        return this.http.post<DataResource<T>>(this.resource, data)
    }

    update<D = any>(id: ID, data: D): Promise<DataResponse<T>> {
        return this.http.put<DataResource<T>>(`${ this.resource }/${ id }`, data)
    }

    delete(id: ID): Promise<DataResponse<T>> {
        return this.http.delete<DataResource<T>>(`${ this.resource }/${ id }`)
    }

}
