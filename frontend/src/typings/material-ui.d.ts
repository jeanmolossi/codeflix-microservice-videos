import { PaletteColor } from '@material-ui/core/styles/createPalette';

declare module '@material-ui/core/styles/overrides' {
    interface ComponentNameToClassKey {
        MUIDataTable: any;
        MUIDataTableToolbar: any;
        MUIDataTableHeadCell: any;
        MuiTableSortLabel: any;
        MUIDataTableSelectCell: any;
        MUIDataTableBodyCell: any;
        MUIDataTableToolbarSelect: any;
        MUIDataTableBodyRow: any;
        MUIDataTablePagination: any;
    }
}

declare module '@material-ui/core/styles/createPalette' {
    import { PaletteColorOptions } from "@material-ui/core";

    interface Palette {
        success: PaletteColor;
    }

    interface PaletteOptions {
        success?: PaletteColorOptions
    }
}
