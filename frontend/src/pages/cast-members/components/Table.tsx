import React, { useEffect, useState } from 'react';
import MUIDataTable, { MUIDataTableColumn } from "mui-datatables";
import { format, parseISO } from 'date-fns';

import { httpVideo } from "../../../util/http";

const castMemberTypes = new Map<number, string>(
    [
        [ 0, "Ator" ],
        [ 1, "Diretor" ],
    ]
);

const columnsDefinition: MUIDataTableColumn[] = [
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
    const [ data, setData ] = useState([]);

    useEffect(() => {
        httpVideo.get('/cast_members').then(({ data }) => setData(data.data))
    }, []);

    return (
        <div>
            <MUIDataTable
                columns={ columnsDefinition }
                data={ data }
                title={ 'Listagem de membros de elencos' }
            />
        </div>
    );
};
