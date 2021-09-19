import React, { useEffect, useState } from 'react';
import { Chip } from '@material-ui/core';
import MUIDataTable, { MUIDataTableColumn } from "mui-datatables";
import { format, parseISO } from 'date-fns';

import { httpVideo } from "../../../util/http";

const columnsDefinition: MUIDataTableColumn[] = [
    {
        name: "name",
        label: "Nome"
    },
    {
        name: "is_active",
        label: "Ativo ?",
        options: {
            customBodyRender: (value) => {
                return value
                    ? <Chip label={ 'Sim' } color={ 'primary' } />
                    : <Chip label={ 'Nao' } color={ 'secondary' } />;
            }
        }
    },
    {
        name: "created_at",
        label: "Criado em",
        options: {
            customBodyRender: (value) => {
                return <span>{ format(parseISO(value), 'dd/MM/yyyy HH:ii') }</span>
            }
        }
    }
];

export const Table = () => {
    const [ data, setData ] = useState([]);

    useEffect(() => {
        httpVideo.get('/categories').then(({ data }) => setData(data.data))
    }, []);

    return (
        <div>
            <MUIDataTable
                columns={ columnsDefinition }
                data={ data }
                title={ 'Listagem de categorias' }
            />
        </div>
    );
};
