import React, { BaseSyntheticEvent, useCallback } from 'react';
import { Box, Button, ButtonProps, Checkbox, makeStyles, TextField } from "@material-ui/core";
import { useForm } from "react-hook-form";
import { Category, categoryHttp } from "../../../util/http/category-http";

type FormProps = {}

type FormFields = Omit<Category, 'id'>;

const useStyles = makeStyles(theme => ({
    submit: {
        marginRight: theme.spacing(1)
    }
}))

export const Form = ({ ..._ }: FormProps) => {
    const classes = useStyles();

    const buttonProps: ButtonProps = {
        className: classes.submit,
        variant: 'contained',
        color: 'secondary'
    }

    const { register, handleSubmit, getValues, watch } = useForm<FormFields>({
        defaultValues: {
            is_active: true
        }
    });

    const onSubmit = useCallback((formData: FormFields, _: BaseSyntheticEvent | undefined) => {
        categoryHttp.create(formData)
            .then((response) =>
                console.log(response.data.data)
            )
    }, []);

    const onSubmitOnly = () => onSubmit(getValues(), undefined)

    return (
        <form onSubmit={ handleSubmit(onSubmit) }>
            <TextField
                name={ 'name' }
                label={ 'Nome' }
                fullWidth
                variant={ 'outlined' }
                inputProps={ {
                    ...register('name')
                } }
            />

            <TextField
                name={ 'description' }
                label={ 'Descrição' }
                multiline
                rows={ 4 }
                fullWidth
                variant={ 'outlined' }
                margin={ 'normal' }
                inputProps={ {
                    ...register('description')
                } }
            />

            <Checkbox
                name={ 'is_active' }
                aria-label={ 'Ativo ?' }
                id={ 'is_active' }
                inputProps={ {
                    ...register('is_active')
                } }
                checked={ watch('is_active') }
                color={ 'primary' }
            />
            <label htmlFor={ 'is_active' }>Ativo ?</label>

            <Box dir={ 'rtl' }>
                <Button { ...buttonProps } onClick={ onSubmitOnly }>Salvar</Button>
                <Button { ...buttonProps } type={ 'submit' }>Salvar e continuar editando</Button>
            </Box>
        </form>
    )
}
