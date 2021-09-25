import React, { useCallback, useMemo } from 'react';
import MUIDataTable, { MUIDataTableColumn, MUIDataTableOptions, MUIDataTableProps } from "mui-datatables";
import { cloneDeep, merge, omit } from 'lodash';
import { MuiThemeProvider, Theme, useMediaQuery, useTheme } from "@material-ui/core";

export interface TableColumn extends MUIDataTableColumn {
    width?: string;
}

interface TableProps extends MUIDataTableProps {
    columns: TableColumn[];
    isLoading?: boolean;
}

export const Table = ( { columns, isLoading = false, ...props }: TableProps ) => {
    const theme = cloneDeep<Theme>( useTheme() );

    const isSmOrDown = useMediaQuery( theme.breakpoints.down( 'sm' ) );

    const setColumnsWidth = useCallback(
        ( columns: TableColumn[] ) => {
            columns.forEach( ( column, key ) => {
                if ( column.width ) {
                    const overrides = theme.overrides as any;
                    overrides
                        .MUIDataTableHeadCell
                        .fixedHeader[ `&:nth-child(${ key + 2 })` ] = {
                        width: column.width
                    }
                }
            } )
        },
        [ theme ]
    );

    const extractMuiDataTableColumns = useCallback(
        ( columns: TableColumn[] ) => {
            setColumnsWidth( columns );
            return columns.map( column => omit( column, 'width' ) )
        },
        [ setColumnsWidth ]
    );

    const newProps = useMemo(
        () => merge(
            { options: cloneDeep( defaultOptions ) },
            props,
            { columns: extractMuiDataTableColumns( columns ) } ),
        [ props, extractMuiDataTableColumns, columns ]
    );

    const applyLoading = useCallback(
        () => {
            const textLabels = newProps.options?.textLabels as any;
            textLabels.body.noMatch = isLoading
                ? 'Carregando...'
                : textLabels.body.noMatch;
        },
        [ isLoading, newProps ]
    );

    const applyResponsive = useCallback(
        () => {
            newProps.options.responsive = isSmOrDown
                ? 'simple'
                : 'standard'
        },
        [ isSmOrDown, newProps ]
    );

    applyLoading();
    applyResponsive();

    return (
        <MuiThemeProvider theme={ theme }>
            <MUIDataTable { ...newProps }/>
        </MuiThemeProvider>
    );
}

const defaultOptions: MUIDataTableOptions = {
    print: false,
    download: false,
    textLabels: {
        body: {
            noMatch: 'Nenhum registro encontrado',
            toolTip: 'Classificar',
        },
        pagination: {
            next: 'Próxima página',
            previous: 'Página anterior',
            rowsPerPage: 'por página',
            displayRows: 'de'
        },
        toolbar: {
            search: 'Busca',
            downloadCsv: 'Download CSV',
            print: 'Imprimir',
            viewColumns: 'Ver colunas',
            filterTable: 'Filtrar tabelas'
        },
        filter: {
            all: 'Todos',
            title: 'FILTROS',
            reset: 'Limpar'
        },
        viewColumns: {
            title: 'Ver colunas',
            titleAria: 'Ver/Esconder colunas da tabela'
        },
        selectedRows: {
            text: 'registro(s) selecionado(s)',
            delete: 'Excluir',
            deleteAria: 'Excluir registros selecionados'
        },
    }
}

export function makeActionStyles(column: number): (theme: Theme) => Theme {
    return (theme: Theme) => {
        const copyTheme = cloneDeep(theme);
        const selector = `&[data-testid^="MuiDataTableBodyCell-${ column }"]`;
        (copyTheme.overrides as any).MUIDataTableBodyCell.root[selector] = {
            paddingTop: 0,
            paddingBottom: 0
        }

        return copyTheme
    }
}
