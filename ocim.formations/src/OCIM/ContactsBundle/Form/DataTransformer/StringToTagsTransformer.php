<?php
namespace OCIM\ContactsBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransevenementFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use OCIM\ContactsBundle\Entity\TagStructure;
use Doctrine\Common\Collections\ArrayCollection;
class StringToTagsTransformer implements DataTransformerInterface
{
	private $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    // transforms the tags object to a string
    public function transform($tags)
    {
        if (null === $tags) {
            return null;
        }
		$str = implode(',', $tags->toArray());
        return $str;
    }

    // transforms the str into an collection of tags
    public function reverseTransform($str)
    {
        if (!$str) {
            return null;
        }
		
		
		$arr = array_map('trim', explode(',', $str));
		$tags = new ArrayCollection();
		
		foreach($arr as $a){
			$tag = $this->om->getRepository('OCIMContactsBundle:TagStructure')->findOneBy(array('tag' => $a));
			if(!$tag){
				$tag = new TagStructure();
				$tag->setTag($a);
			}
			$tags->add($tag);
		}
		
		return $tags;
    }
}