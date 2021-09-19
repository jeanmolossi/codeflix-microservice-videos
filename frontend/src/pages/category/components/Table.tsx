import React, { useEffect, useState } from 'react';
import MUIDataTable, { MUIDataTableColumn } from "mui-datatables";
import { format, parseISO } from 'date-fns';

import { Category, categoryHttp } from "../../../util/http/category-http";
import { BadgeNo, BadgeYes } from "../../../components";

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
                    ? <BadgeYes />
                    : <BadgeNo />;
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
    const [ data, setData ] = useState<Category[]>([]);

    useEffect(() => {
        categoryHttp.list()
            .then((response) =>
                setData(response.data.data)
            )
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
