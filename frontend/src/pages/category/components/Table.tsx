import React, { useEffect, useState } from 'react';
import { format, parseISO } from 'date-fns';

import { categoryHttp } from "../../../util/http/category-http";
import { BadgeNo, BadgeYes, Table as MyTable, TableColumn } from "../../../components";
import { Category } from "../../../core/models";
import { useSnackbar } from "notistack";

const columnsDefinition: TableColumn[] = [
    {
        name: 'id',
        label: 'ID',
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
            customBodyRender: ( value ) => {
                return <span>{ format( parseISO( value ), 'dd/MM/yyyy HH:ii' ) }</span>
            }
        }
    },
    {
        name: "actions",
        label: "Ações",
    }
];

export const Table = () => {
    const snackbar = useSnackbar();
    const [ loading, setLoading ] = useState( false );
    const [ data, setData ] = useState<Category[]>( [] );

    useEffect( () => {
        setLoading( true )
        categoryHttp.list()
            .then( ( response ) =>
                setData( response.data.data )
            )
            .catch( ( err ) => {
                snackbar.enqueueSnackbar(
                    err.message,
                    { variant: "error" }
                );
            } )
            .finally( () => setLoading( false ) )
    }, [ snackbar ] );

    return (
        <div>
            <MyTable
                columns={ columnsDefinition }
                data={ data }
                title={ 'Listagem de categorias' }
                isLoading={ loading }
                options={ { responsive: "simple" } }
            />
        </div>
    );
};
