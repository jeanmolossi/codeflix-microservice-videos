import React, { useEffect, useState } from 'react';
import { format, parseISO } from 'date-fns';
import { useSnackbar } from "notistack";
import { IconButton, MuiThemeProvider } from "@material-ui/core";
import { Edit as EditIcon } from "@material-ui/icons";
import { categoryHttp } from "../../../util/http/category-http";
import { BadgeNo, BadgeYes, makeActionStyles, Table as MyTable, TableColumn } from "../../../components";
import { Category } from "../../../core/models";
import { Link } from "react-router-dom";


const columnsDefinition: TableColumn[] = [
    {
        name: 'id',
        label: 'ID',
        width: '30%',
        options: {
            sort: false
        }
    },
    {
        name: "name",
        label: "Nome",
    },
    {
        name: "is_active",
        label: "Ativo ?",
        options: {
            customBodyRender: ( value ) => {
                return value
                    ? <BadgeYes/>
                    : <BadgeNo/>;
            },
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
    },
    {
        name: "actions",
        label: "Ações",
        options: {
            sort: false,
            customBodyRender: (value, tableMeta) => {
                const [ id ] = tableMeta.rowData;
                return (
                    <IconButton
                        color={ 'secondary' }
                        component={ Link }
                        to={ `/categorias/${ id }/editar` }
                    >
                        <EditIcon fontSize={ 'inherit' } />
                    </IconButton>
                )
            }
        }
    }
];

export const Table = () => {
    const snackbar = useSnackbar();
    const [ loading, setLoading ] = useState(false);
    const [ data, setData ] = useState<Category[]>([]);

    useEffect(() => {
        setLoading(true)
        categoryHttp.list()
            .then((response) =>
                setData(response.data.data)
            )
            .catch((err) => {
                snackbar.enqueueSnackbar(
                    err.message,
                    { variant: "error" }
                );
            })
            .finally( () => setLoading( false ) )
    }, [ snackbar ] );

    return (
        <MuiThemeProvider theme={ makeActionStyles(columnsDefinition.length - 1) }>
            <div>
                <MyTable
                    columns={ columnsDefinition }
                    data={ data }
                    title={ 'Listagem de categorias' }
                    isLoading={ loading }
                    options={ { responsive: "simple" } }
                />
            </div>
        </MuiThemeProvider>
    );
};
