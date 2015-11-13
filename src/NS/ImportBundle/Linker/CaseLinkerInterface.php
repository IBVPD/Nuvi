<?php

namespace NS\ImportBundle\Linker;


interface CaseLinkerInterface
{
    /**
     * Array of fields that will be sent to the doctrine writer as a way to attempt
     * to retrieve the case/object being imported
     *
     * @return array
     */
    public function getCriteria();

    /**
     * This *optionally* returns the name of a repository function that is expecting
     * the criteria fields and will locate the record based on those fields.
     *
     * @return string|null
     */
    public function getRepositoryMethod();

    /**
     * @return mixed
     */
    public function getName();
}
