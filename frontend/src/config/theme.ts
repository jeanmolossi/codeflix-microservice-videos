import { createTheme, SimplePaletteColorOptions } from "@material-ui/core";
import { PaletteOptions } from "@material-ui/core/styles/createPalette";
import { green, red } from "@material-ui/core/colors";

const palette: PaletteOptions = {
    primary: {
        main: '#79aec8',
        contrastText: '#ffffff'
    },
    secondary: {
        main: '#4db5ab',
        contrastText: '#ffffff',
        dark: '#055a52'
    },
    background: {
        default: '#fafafa'
    },
    success: {
        main: green["500"],
        contrastText: '#ffffff'
    },
    error: {
        main: red.A400
    }
};

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
                color: getSimpleColor('primary', 'main'),
                '&:hover, &:active, &.focus': {
                    color: getSimpleColor('secondary', 'dark'),
                },
            },
            iconActive: {
                color: getSimpleColor('secondary', 'dark'),
                '&:hover, &:active, &.focus': {
                    color: getSimpleColor('secondary', 'dark'),
                },
            },
        },
        MUIDataTableHeadCell: {
            fixedHeader: {
                paddingTop: 8,
                paddingBottom: 8,
                backgroundColor: getSimpleColor('primary', 'main'),
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
                backgroundColor: getSimpleColor('primary', 'main'),
                '& span': {
                    color: '#ffffff !important'
                }
            }
        },
        MUIDataTableBodyCell: {
            root: {
                color: getSimpleColor('secondary', 'main'),
                '&:hover, &:active, &.focus': {
                    color: getSimpleColor('secondary', 'main')
                },
            }
        },
        MUIDataTableToolbarSelect: {
            title: {
                color: getSimpleColor('primary', 'main')
            },
            iconButton: {
                color: getSimpleColor('primary', 'main')
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
                color: getSimpleColor('primary', 'main')
            }
        }
    }
});

function getSimpleColor(
    paletteColor: keyof PaletteOptions,
    key: keyof SimplePaletteColorOptions,
    customPalette: PaletteOptions = palette
): string {
    return (customPalette[paletteColor] as SimplePaletteColorOptions)[key] as string;
}
