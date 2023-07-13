<?php

namespace App\ViewModel;

final class TagVm
{
    private string $intitule;
    private string $colorHexa;
	private string $slug;

	public function __construct(
        string $slug,
        string $intitule,
        string $colorHexa
    ) {
        $this->slug = $slug;
        $this->intitule = $intitule;
        $this->colorHexa = $colorHexa;
    }

    /**
     * @return string
     */
    public function getIntitule(): string
    {
        return $this->intitule;
    }

    /**
     * @return string
     */
    public function getColorHexa(): string
    {
        return $this->colorHexa;
    }

	public function getSlug(): string
	{
		return $this->slug;
	}
}
