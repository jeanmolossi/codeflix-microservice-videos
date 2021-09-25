import React from 'react';
import { Page } from "../../components";
import { Form } from "./components";
import { useParams } from "react-router-dom";

export const CreateCategory = () => {
    const { id } = useParams<{ id?: string }>();

    return (
        <Page title={ id ? 'Editar categoria' : 'Criar categoria' }>
            <Form />
        </Page>
    )
}
