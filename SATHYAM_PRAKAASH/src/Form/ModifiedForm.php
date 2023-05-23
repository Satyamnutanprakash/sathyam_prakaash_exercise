<?php

namespace Drupal\sathyam_prakaash\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class ModifiedForm extends FormBase {

    // generated form id
    public function getFormId()
    {
        return 'modified_form_form_details';
    }

    // build form generates form
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['firstname'] = [
            '#type' => 'textfield',
            '#title' => 'First Name',
            '#required' => true,
            '#placeholder' => 'First Name',
        ];
        $form['lastname'] = [
            '#type' => 'textfield',
            '#title' => 'Last Name',
            '#required' => false,
            '#placeholder' => 'Last Name',
        ];
        $form['gender'] = [
            '#type' => 'select',
            '#title' => 'Gender',
            '#options' => [
                'male' => 'Male',
                'female' => 'Female',
            ],
        ];
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => 'Submit',
        ];
        return $form;
    }


    // submit form
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        \Drupal::messenger()->addMessage("Your Details Submitted Successfully");
        \Drupal::database()->insert("form_details")->fields([
            'firstname' => $form_state->getValue("firstname"),
            'lastname' => $form_state->getValue("lastname"),
            'gender' => $form_state->getValue("gender"),
        ])->execute();
    }
}


