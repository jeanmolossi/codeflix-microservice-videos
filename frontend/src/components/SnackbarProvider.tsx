import React, { useMemo } from 'react';
import { SnackbarProvider as NotistackSnackbarProvider, SnackbarProviderProps } from 'notistack';
import { IconButton, makeStyles } from "@material-ui/core";
import CloseIcon from "@material-ui/icons/Close";

enum Time {
    Second = 1000
}

const useStyles = makeStyles((theme) => ({
    variantSuccess: {
        backgroundCOlor: theme.palette.success.main
    },
    variantError: {
        backgroundCOlor: theme.palette.error.main
    },
    variantInfo: {
        backgroundCOlor: theme.palette.primary.main
    },
}))

export const SnackbarProvider = ({ children, ...rest }: SnackbarProviderProps) => {
    let snackbarProviderRef: NotistackSnackbarProvider | null;

    const classes = useStyles();

    const defaultProps: SnackbarProviderProps = useMemo(() => ({
        classes,
        autoHideDuration: 3 * Time.Second,
        maxSnack: 3,
        anchorOrigin: {
            horizontal: 'right',
            vertical: 'top'
        },
        ref(el) {
            // eslint-disable-next-line react-hooks/exhaustive-deps
            snackbarProviderRef = el
        },
        action(key) {
            return (
                <IconButton
                    color={ 'inherit' }
                    style={ { fontSize: 20 } }
                    onClick={
                        () => snackbarProviderRef?.closeSnackbar(key)
                    }
                >
                    <CloseIcon />
                </IconButton>
            )
        },
        children
    }), [])

    const newProps = { ...defaultProps, ...rest };

    return (
        <NotistackSnackbarProvider { ...newProps }>
            { children }
        </NotistackSnackbarProvider>
    )
}

