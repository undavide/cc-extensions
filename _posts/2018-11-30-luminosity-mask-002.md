---
title: 'Luminosity Mask Done Right!'
date: 	2018-11-30
author: Fixel Algorithms
layout: post
class:  news
hidden: false
---
![Luminosity Mask 002][1]

In our previous post [Luminosity Mask - How Does It (Really) Work?][3] we explained the theory behind Luminosity Mask.  
The theory basically sums to the fact that Luminosity Mask is a remapping of values of a Graysacle image.

In this post, as promised at the end of previous post, we will explore what happens behind Photoshop based Luminosity Mask generators.  
We will see that utilizing Photoshop's engine to create Luminosity Masks in the methods used by most create some artifact we better avoid.  
In order to show how to avoid them we'll suggest an idea and an implementation in the form of a Photoshop Plug In named - [Fixel Zone Selector][2].

We will also talk about the trade off in the essence of each Luminosity Mask creation - Smooth vs. Narrow (Focused).

## Arrangements

### Reference Image
This posts will include many _hands on_ tests in Photoshop.  
Hence a Reference Image is needed (Feel free to download it and replicate the tests and analysis).

![][Figure001]{:class="center-img"}

The synthetic image above is a perfect Grayscale Gradient of 8 Bit Image created programmatically.  
It contains all values {0, 1, 2, ..., 254, 255}. The red box contains the line which will be processed as one dimensional function.  
This will assist us analyze what happens exactly on every single Photoshop operation done since all operations are Pixel Wise.

We will also use a "Real World" image to display results. We'll use the same image from previous post.

 ![Simple Living][Figure002]{:class="center-img"}

### Grayscale and Color Modes Gamma Settings in Photoshop

There is one tricky thing to take into account when working with Photoshop on *Masks* and *Channels*.  
Masks and Channels are considered to be "Grayscale" image in Photoshop.  
Since the creation of Lumionsoity Masks involves manipulating (Doing Math on) Channels / Masks the Color Profile matters.

In our post, for simplification, we'll use the `sRGB` color profile for all RGB images.  
Yet we expect Grayscale image in RGB (3 Channels which are identical since the image is Graysacle) to match Grayscale (Single Channel) version of it.  
Hence one must synchronize the Grayscale Color Profile of RGB images and Grayscale Images in Photoshop.

<br>

![][Figure003]{:class="center-img"}

<br>

The suggested matching is given by:

<br>

{:class="table table-bordered"}
| RGB Space    	| Gray Space     	| Gamma            	|
|--------------	|----------------	|------------------	|
| sRGB         	| sGray          	| sRGB Gamma Curve 	|
| Adobe RGB    	| Gray Gamma 2.2 	| 2.2              	|
| ProPhoto RGB 	| Gray Gamma 1.8 	| 1.8              	|

<br>

Since for this demonstration we use `sRGB` we configured Grayscale Color Space to [sGray Color Mode](http://retrofist.com/sgray/).  
We won't get into too much details, yet this is a crucial step and any one not doing it creates miss match in the Math employed.  
This is one of the artifact some of the current generators of Luminosity Masks ignore which means their Math in the Channels tab doesn't match the properties of the RGB image.  

**Remark**
Basically one need to match the Gamma Function employed on the RGB images to the one used for Grayscale Images.  
For the above we chose the `sRGB` Gamma Correction (By selecting `sGray`), those who use `Adobe RGB` should use `Gray Gamma 2.2` for Grayscale and for `ProPhoto RGB` one should use `Gray Gamma 1.8` for Grayscale to match the functions.

In order to show the effect of this Gamma Function applied we used our reference image.  
We loaded it into Photoshop and converted it into Grayscale image using `Image -> Mode -> Grayscale`.  
We did it once with the Default setting and the other time with the `sGray` settings.  
We saved the output image and analyzed the result.

![][Figure004]{:class="center-img"}

In the figure above one could see the effect of setting incompatible color profile. The values are altered without any intention of the user. To understand this flaw in the context of Luminosity Mask it means that the mask is not aligned with the intention of the user. The Highlights mask for example has lower values than expected (200 is mapped to ~180 instead of 200).

<!--
![test](https://i.imgur.com/mcnGYL4.png){:class="center-img"}
<figcaption>
Try this...
</figcaption>
</figure>
-->

## The Classic Luminosity Masks

Luminosity Masks are generated, usually, using Channel Operations. There are 3 main operations: Addition, Subtraction and Multiplication (Intersection).

<br>

{:class="table table-bordered"}
| Operation                               | Keyboard Shortcut                                                              	| Remarks                                           	|
|----------------------------------------	|--------------------------------------------------------------------------------	|---------------------------------------------------	|
| Activate Selection from Channel        	| `Ctrl + Left Mouse Click` on channel to activate channel from                  	| Activate selection by pixels value of the channel 	|
| Add to Current Active Selection        	| Hold `Ctrl + Shift` and `Left Mouse Click` on the channel to add               	| Effectively adds the 2 channels                   	|
| Subtract from Current Active Selection 	| Hold `Ctrl + Alt` and `Left Mouse Click` on the channel to subtract            	| Effectively subtract the 2 channels               	|
| Multiply Current Active Selection      	| Hold `Ctrl + Alt + Shift` and `Left Mouse Click` on the channel to Multiply by 	| Effectively multiply the 2 channels               	|

<br>

In the process of making this post we downloaded the free offerings of all 3 major Luminosity Mask panels.  
After evaluating their results few notes:

 1. All of them used the Luminosity Channel (`Ctrl + Right Click` on RGB Channel in Channels Tab) as base for the Luminosity Mask generation.
 2. All of them generated the exact same result as described in [Luminosity Mask KickStarter Guide][4]. For clarity we'll use the notations of `Light00#`, `Mid00#` and `Dark00#` to refer to the `#` Highlights / Midtones / Shadows Luminosity Mask.
 3. None of them tried or suggested to align the RGB Color Space to Grayscale prior to Luminosity Mask generation.

The above means that in case the user didn't align the color space the Math operations used by the generators are flawed. Namely the Grayscale image used for calculations isn't the Luminosity Mask but one with different Gamma Function applied on.

### Flawed Math

Even assuming the user (Or the Luminosity Mask generator used) aligned the Color Space between the RGB Color Space and Grayscale Color Space we will show the Math of Luminosity Mask in Photoshop is flawed.  
In the previous post we mentioned a small teaser - How come the Math of the Midtones suggest that `Mid001` should be all black (All values are zero) while Mask Generators generates `Mid001` which is clearly not all black.

Let's go through the process of generating `Light001`, `Dark001` and `Mid001` on the reference image:

 1. Load the reference image into Photoshop.
 2. Move into the *Channels* tab in Photoshop.
 3. Use `Ctrl + Left Mouse Click` on the RGB Channel to activate *Luminosity Selection*. Create new channel using selection by `Select -> Save Selection` (Or the small icon at the bottom `Save Selection as Channel`). Call this channel `Light001`. Since the reference image is Grayscale the Luminosity Channel is exactly it (Assuming matching RGB and Grayscale Color Space). Namely $ f \left( x \right) = x $.
 4. Clear selection by `Select -> Deselect` (Or `Ctrl + D`).
 5. Duplicate the channel `Light001` (Right Mouse Click) and name the new channel as `Dark001`. Make it the active channel and click `Ctrl + I` (Invert layer / channel). This applies $ f \left( x \right) = 255 - x $. Namely it will create a reversed gradient image.
 6. Activate the RGB channel (By clicking on it) and *Select All* by `Select -> All` (Or by `Ctrl + A`). This create in background a Mask of Select All (All White - 255).
 7. Subtract the `Light001` Channel by holding `Ctrl + Alt` and clicking on the `Light001` channel. This will subtract from the Select All (All white) selection the Highlights Selection. Namely $ f \left( x \right) = 255 - x $. Yes, indeed this should be the `Dark001`, we'll see in a second about that.
 8. Subtract the `Dark001` Channel by holding `Ctrl + Alt` and clicking on the `Dark001` channel. Photoshop might alert you that no selection with more than 50% was made. Now, let's see what we expect - $ f \left( x \right) = 255 - x - (255 - x) $ this should be 0.
 9. Save selection into new channel as in **Step 3** and name it `Mid001`. Is it pure black?

 The is a replication of the [guide][4] or [video][5].  
 Yet, unlike the Math, the result isn't 0, so what's going on?

![][Figure005]{:class="center-img"}

Let's go through this again. In the figure above one could see the result using Photoshop and using programming of the results. It seems that both Photoshop and the programming calculation agree on the first 2 steps (Hence the lines hide each other on the last row for the two left plots) yet the end result is different. The programming result says, as Math, that the output of `Mid001` should be all black (Zero) yet Photoshop's result isn't zero (For those who are curious, we'll solve what happens later on, as a teaser, this is a multiplication, not a subtraction).

#### Results for Color Space Miss Match

We did the same experiment yet while the Gray Color Space of Photoshop is set to Dot Gain 20%.

![][Figure006]{:class="center-img"}

As one could see, even the inversion of the channel created differences in the result which gets worse with each additional Math operation.  
This can be shown in the generation of the Midtone Mask (`Mid001`) which is not only different from black but shifted and not centered.

#### What Does Photoshop Actually Do?

You ask what Photoshop does? Multiplication...  
Yes, `Mid001` equals to the Multiplication (Intersection) of `Light001` and `Dark001`.  
We can see it by generating `Mid001` from the intersection of `Light001` and `Dark001`:

 1. Activate selection of `Light001` by `Ctrl + Left Mouse Click` on `Light001` Channel created earlier.
 2. While holding `Ctrl + Alt + Shift` apply `Left Mouse Click` on `Dark001`.
 3. Save selection to a new channel.

The above is exactly the `Mid001` Mask created before.  
So basically when we subtracted 2 selections from the Select All selection what really happened is the we got the intersection of the 2.  
The funny things is that for other cases with levels `002` it doesn't hold.
How come Photoshop do that? Well, only Adobe knows.  

### Comparison of Luminosity Masks

This section compares of of the most popular Luminosity Masks Generator vs. Theory (Fixel).

#### Grayscale Color Space - `Dot Gain 20%`

This section shows results when Photoshop is using its default settings (Grayscale Color Space - `Dot Gain 20%`) as we assume this is what most users will encounter:

![][Figure007]{:class="center-img"}

In the figure above one could see 2 phenomenons:

 1. The use of the invalid color profile (`Dot Gain 20%`) create shifting of the mask.
 2. The tonal curve created in Photoshop isn't as smooth as theory and contains some rough edges due to quantization.


#### Grayscale Color Space - `sGray`

This section shows results when Photoshop is configured correctly.

![][Figure008]{:class="center-img"}

While setting the correct Color Profile, as can be seen in the figure above, fixes the Tonal Shift the non smooth curves remains as a limitation of using Photoshop's Channel Calculations.  
Those artifacts can and should be avoided using better Math Engine then Photoshop's Channel operations which are the limitation of the Masks Generated by tools in the market.  
Another thing is that Phtooshop doesn't stick to the Math. Namely even intersection of a mask with itself doesn't yield the correct values of multiplication.

## Suggested Solution

Well, solution is simple, use the image itself as basis to the Math manipulations.  
One way to achieve that is using the correct Color Space and then use different tools in Photoshop such as Curves (Which has other limitations).  
Another approach is to have a different Math Engine for those calculations and this can be achieved using Plug In's.

### Plug In Capabilities

Plug In can also suggest capabilities which are very hard to replicate using Channel Operation.  
Let's say the user want to select specific tonal range?  
Most tools out there allow doing so implicitly by Addition / Subtraction of the given predefined masks.  
Plug In solution can generate the required result explicitly.  
For instance, the following can be easily achieved using [Fixel Zone Selector][2]:

![][Figure009]{:class="center-img"}

As one could see in the figure above, [Fixel Zone Selector][2] can generate Luminosity Masks which are arbitrary to the user will while being:
 * 	Focused - Can target specific luminosity level.
 *	Smooth - The curve is smooth (Using 32 Bit Float calculations regardless of the image) with no quantization error.
 *	Direct - Works directly on the image without overhead of calculations or sensitivity to the Gray Scale Color Profile of Photoshop.

Namely, [Fixel Zone Selector][2], by utilizing its own Luminosity Mask Generator, can overcome all issues mentioned above in Photoshop.  
Since it has a simple, intuitive, yet powerful UI, the user can have an effective tool to totally control the Luminosity Mask generation process.

## Summary

In this analysis we have shown the issues with the current Luminosity Masks generators on the market which uses Channels Operations (Which are most of them if not all).  
Some of those are related to the Color Profile Settings in Photoshop and hence can be eliminated using the correct configuration.  
Yet, even with the correct settings, when using Photoshop built in tools for Luminosity Mask generation there are many limitations to what can be done and in what quality.  
Being aware of the limitations, as always, is half the way, using the better tools is the other half.


**Remark**  
We used free Panels / Scripts by major vendors as of April 2018.

## Image Credit
 *  [Lighthouse Image](https://www.flickr.com/photos/magnetismus/8399258607/) - Credit to [magnetismus](https://www.flickr.com/people/magnetismus/).
 *  [Schwaigsee Lake](https://www.freeimages.com/photo/schwaigsee-lake-1342788) - Credit to [Alfred Borchard](https://www.freeimages.com/photographer/Alfi007-51075).
 *  [Simple Living](https://www.flickr.com/photos/lightsamples/22552453147) - Credit to [Malcolm Carlaw](https://www.flickr.com/photos/lightsamples/).


## Resources
 *  [Luminosity Mask: The Complete Kickstarter’s Guide](http://fotographee.com/tutorial-image-editing-luminosity-masks/).
 *  [Video - How to Generate the Classic Luminosity Masks Using Mask / Channel Operations (Add, Subtract, Intersect [Multiply])](https://www.youtube.com/watch?v=xvjno4d8uJ8).
 *  [Video - How to Generate the Classic Luminosity Masks Using Calculations (16 Bit Mode)](https://www.youtube.com/watch?v=43JbFIOckrM).
 *  [Video - Selecting Using Luminosity Masks (Using Curve Tool)](https://www.youtube.com/watch?v=la-zWPwjuQw).
 *	[Luminosity Mask - How Does It (Really) Work?](https://fixelalgorithms.co/news/2018/03/luminosity-mask-001/index.html) - Introduction to Luminosity Mask concept.


<!-- This is commented out -->
[comment]: # (https://fstoppers.com/education/how-create-luminosity-masks-better-retouching-111111 for Luminosity Mask using Calculations.)
[comment]: # (https://www.capturelandscapes.com/introduction-to-luminosity-masks/.)
[comment]: # (https://fstoppers.com/composite/create-seamless-selections-using-luminosity-masks-159068.)
[comment]: # (https://www.psdbox.com/tutorials/complex-masking-using-channels-and-calculations.)
[comment]: # (https://www.youtube.com/watch?v=t3zSUK7KK7c)
[comment]: # (https://www.youtube.com/watch?v=htuf4yaanBI)
[comment]: # (http://play.macprovideo.com/photoshop-102-selection-masking-techniques/12)
[comment]: # (https://www.youtube.com/watch?v=QUkIgdmnBaE)
[comment]: # (http://fotographee.com/luminosity-masks-gradient/ See shortcuts for operations on Masks)
[comment]: # (https://www.youtube.com/watch?v=XGe_YC5dIwI Gamma in Luminosity Masks)

Key Words: [Fixel Algorithms][2], [Fixel][2], [Fixel Zone Selector][2], [Luminosity Mask][2],  [Luminosity Masks][2], [Saturation Mask][2], [Saturation Masks][2], [Curves][2], [Levels][2], [Luminosity][2], [Photoshop][2], [Plug In][2].


<!-- This is commented out -->
  [1]: {{site.baseurl}}/news/images/LuminosityMask001/BlogPostIcon.png "Luminosity Mask 001"
  [2]: {{site.baseurl}}/products/zoneselector/ "Fixel Zone Selector 1 PS Product Page"
  [3]: {{site.baseurl}}/news/2018/03/luminosity-mask-001 "Luminosity Mask - How Does It (Really) Works?"
  [4]: http://fotographee.com/tutorial-image-editing-luminosity-masks/ "Luminosity Mask: The Complete Kickstarter’s Guide"
  [5]: https://www.youtube.com/watch?v=xvjno4d8uJ8 "How to Generate the Classic Luminosity Masks Using Mask / Channel Operations (Add, Subtract, Intersect [Multiply])"
  [Figure001]: {{site.baseurl}}/news/images/LuminosityMask002/ReferenceImage.png "Figure 001 - Reference Image"
  [Figure002]: {{site.baseurl}}/news/images/LuminosityMask002/SimpleLiving.png "Figure 002 - Real World Reference Image"
  [Figure003]: {{site.baseurl}}/news/images/LuminosityMask002/PhotoshopColorSettings.png "Figure 003 - Photoshop Color Profile Settings"
  [Figure004]: {{site.baseurl}}/news/images/LuminosityMask002/ColorProfileOutputValue.png "Figure 004 - Photoshop Color Profile - Pixel Values"
  [Figure005]: {{site.baseurl}}/news/images/LuminosityMask002/GeneratingLightDarkMidCorrectColorSpace.png "Figure 005 - Luminosity Masks Light, Dark and Mid Under Correct Color Profile Settings"
  [Figure006]: {{site.baseurl}}/news/images/LuminosityMask002/GeneratingLightDarkMidWrongColorSpace.png "Figure 006 - Luminosity Masks Light, Dark and Mid Under Wrong Color Profile Settings"
  [Figure007]: {{site.baseurl}}/news/images/LuminosityMask002/PhotoshopDotGainVsFixelLuminosityMaskAnimated.png "Figure 007 - Luminosity Masks - Photoshop (Color Profile - Dot Gain 20%) vs. Theory (Fixel)"
  [Figure008]: {{site.baseurl}}/news/images/LuminosityMask002/PhotoshopSGrayVsFixelLuminosityMaskAnimated.png "Figure 008 - Luminosity Masks - Photoshop (Color Profile - sGray) vs. Theory (Fixel)"
  [Figure009]: {{site.baseurl}}/news/images/LuminosityMask002/FixelZoneSelectorParametersAnimated.png "Figure 009 - Luminosity Masks - Fixel Zone SelectorMask Generation Capabilities"
