<?php

interface UserInterface
{
    /**
     * Returns implementing model with corresponding User model.
     */
    public function withUser();

    /**
     * Find implementing model with corresponding User model.
     */
    public function findWithUser();
}
