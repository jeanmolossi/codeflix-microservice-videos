import React from 'react';
import { TextField, Checkbox, Box, Button, ButtonProps, makeStyles } from "@material-ui/core";

type FormProps = {}

const useStyles = makeStyles(theme => ({
    submit: {
        marginRight: theme.spacing(1)
    }
}))

export const Form = ({}: FormProps) => {
    const classes = useStyles();

    const buttonProps: ButtonProps = {
        className: classes.submit,
        variant: 'outlined',
    }

    return (
        <form>
            <TextField
                name={ 'name' }
                label={ 'Nome' }
                fullWidth
                variant={ 'outlined' }
            />

            <TextField
                name={ 'description' }
                label={ 'Descrição' }
                multiline
                rows={ 4 }
                fullWidth
                variant={ 'outlined' }
                margin={ 'normal' }
            />

            <Checkbox
                name={ 'is_active' }
                aria-label={ 'Ativo ?' }
                id={ 'is_active' }
            />
            <label htmlFor={ 'is_active' }>Ativo ?</label>

            <Box dir={ 'rtl' }>
                <Button { ...buttonProps }>Salvar</Button>
                <Button { ...buttonProps } type={ 'submit' }>Salvar e continuar editando</Button>
            </Box>
        </form>
    )
}
