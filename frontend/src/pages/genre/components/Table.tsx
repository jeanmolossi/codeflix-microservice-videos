import React, { useEffect, useState } from 'react';
import MUIDataTable, { MUIDataTableColumn } from "mui-datatables";
import { format, parseISO } from 'date-fns';

import { httpVideo } from "../../../util/http";

const columnsDefinition: MUIDataTableColumn[] = [
    {
        name: "name",
        label: "Nome"
    },
    {
        name: "categories",
        label: "Categorias",
        options: {
            customBodyRender: (values) => {
                return values?.map((category: any) => category.name).join(', ')
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
        httpVideo.get('/genres').then(({ data }) => setData(data.data))
    }, []);

    return (
        <div>
            <MUIDataTable
                columns={ columnsDefinition }
                data={ data }
                title={ 'Listagem de gênero' }
            />
        </div>
    );
};
