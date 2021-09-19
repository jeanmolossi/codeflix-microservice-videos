import { createTheme, SimplePaletteColorOptions } from "@material-ui/core";
import { PaletteOptions } from "@material-ui/core/styles/createPalette";

const palette: PaletteOptions = {
    primary: {
        main: '#79aec8',
        contrastText: '#ffffff'
    },
    secondary: {
        main: '#4db5ab',
        contrastText: '#ffffff'
    },
    background: {
        default: '#fafafa'
    }
};

const primaryMain = (palette!.primary as SimplePaletteColorOptions).main;
const secondaryMain = (palette!.secondary as SimplePaletteColorOptions).main;

export const theme = createTheme({
    palette,
    overrides: {
        MUIDataTable: {
            paper: {
                boxShadow: 'none'
            }
        },
        MUIDataTableToolbar: {
            root: {
                minHeight: '58px',
                backgroundColor: palette!.background!.default
            },
            icon: {
                color: primaryMain,
                '&:hover, &:active, &.focus': {
                    color: '#055a52'
                },
            },
            iconActive: {
                color: '#055a52',
                '&:hover, &:active, &.focus': {
                    color: '#055a52'
                },
            },
        },
        MUIDataTableHeadCell: {
            fixedHeader: {
                paddingTop: 8,
                paddingBottom: 8,
                backgroundColor: primaryMain,
                color: '#ffffff',
                '&[aria-sort]': {
                    backgroundColor: '#459ac4'
                }
            },
            sortActive: {
                color: '#ffffff'
            },
            sortAction: {
                color: '#ffffff',
                alignItems: 'center'
            },
            sortLabelRoot: {
                '& svg': {
                    color: '#ffffff !important'
                }
            }
        },
        MUIDataTableSelectCell: {
            headerCell: {
                backgroundColor: primaryMain,
                '& span': {
                    color: '#ffffff !important'
                }
            }
        },
        MUIDataTableBodyCell: {
            root: {
                color: secondaryMain,
                '&:hover, &:active, &.focus': {
                    color: secondaryMain
                },
            }
        },
        MUIDataTableToolbarSelect: {
            title: {
                color: primaryMain
            },
            iconButton: {
                color: primaryMain
            }
        },
        MUIDataTableBodyRow: {
            root: {
                '&:nth-child(odd)': {
                    backgroundColor: palette!.background!.default
                }
            }
        },
        MUIDataTablePagination: {
            root: {
                color: primaryMain
            }
        }
    }
});
