import React, { useEffect, useState } from 'react';
import { format, parseISO } from 'date-fns';
import { Table as MyTable, TableColumn } from '../../../components';
import { castMemberHttp } from "../../../util/http/cast-member-http";
import { CastMember } from "../../../core/models";
import { useSnackbar } from "notistack";

const castMemberTypes = new Map<number, string>(
    [
        [ 0, "Ator" ],
        [ 1, "Diretor" ],
    ]
);

const columnsDefinition: TableColumn[] = [
    {
        name: "name",
        label: "Nome"
    },
    {
        name: "type",
        label: "Tipo",
        options: {
            customBodyRender: (type: number) => {
                return castMemberTypes.get(type) || 'Nao encontrado'
            }
        }
    },
    {
        name: "created_at",
        label: "Criado em",
        options: {
            customBodyRender: (created_at: string) => {
                return <span>{ format(parseISO(created_at), 'dd/MM/yyyy HH:ii') }</span>
            }
        }
    }
];

export const Table = () => {
    const snackbar = useSnackbar();
    const [ loading, setLoading ] = useState(false);
    const [ data, setData ] = useState([] as CastMember[]);

    useEffect(() => {
        setLoading(true)
        castMemberHttp.list()
            .then(({ data }) => setData(data.data))
            .catch((err) => {
                snackbar.enqueueSnackbar(
                    err.message,
                    { variant: "error" }
                );
            })
            .finally(() => setLoading(false))
    }, [ snackbar ]);

    return (
        <div>
            <MyTable
                columns={ columnsDefinition }
                data={ data }
                title={ 'Listagem de membros de elencos' }
                isLoading={ loading }
            />
        </div>
    );
};
