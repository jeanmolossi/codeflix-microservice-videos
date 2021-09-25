import React from 'react';
import { Page } from "../../components";
import { Form } from "./components";
import { useParams } from "react-router-dom";

export const CreateCastMember = () => {
    const { id } = useParams<{ id?: string }>();

    return (
        <Page title={ id ? 'Editar membro de elenco' : 'Criar membro de elenco' }>
            <Form />
        </Page>
    )
}
