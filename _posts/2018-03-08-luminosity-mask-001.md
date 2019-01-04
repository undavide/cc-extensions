---
title: 'Luminosity Mask - How Does It (Really) Work?'
date: 	2018-03-08
author: Fixel Algorithms
layout: post
class:  news
hidden: false
---
![Luminosity Mask 001][1]

Everywhere you go you read about Luminosity Masks.  
I'm really surprised it doesn't have its own Wikipedia Page (Maybe we'll take care of that).  
It is truly a great tool to have in your toolbox, very powerful when used correctly.  
Its greatness by its simplicity.

But how does it works? Not in the sense how to create it, but what really happens?  
Well, let's try answering that and doing it deep (Hopefully interesting).

## The Basics

### Step 001 - It All Starts with a Grayscale Image
You can have image with all the colors in the world but the first step in creating Luminosity Mask is by having a Grayscale image (Single Channel Image).
In most cases it means to calculate the Luminosity Channel of the given image.  
Each one with his own recipe (Most actions / panels based solution uses Photoshop Luminosity selection - `Ctrl + Left Mouse Click` on the RGB Channel in the Channel Tab).

<br/>
![Figure 001][Figure001]{:class="center-img"}
<br/>

By the way, any "Single Channel" image can do here.  
The best cohice (Channel or processed grayscale layer) is the one where the image features you want to select are more evident and easily separable from the rest.  
It could be the Blue from RGB, the Magenta from CMYK, whatever, be creative.

Well, in case of a grayscale image, life is easier - one could just step over to Step 002.

### Step 002 - Apply Pixel Wise Transformation on the Grayscale Image

Now, this is where the magic happens.  
The idea is very simple, given the Gray Scale image as input, the output per pixel is a function of its value only.

Well, this sentence might take some of us back to horrible school days but it is really simple when you think about it.  
Pixel comes in, states its value and gets an output value based only on its value, and voilà we have a Luminosity Mask.  
The name says it all, the Mask depends solely on the Luminosity (Tonal Range value) of the pixels.  
It has nothing to do with their location, not their surrounding pixels.  
Just using the Luminosity value. Nothing more, nothing less. Power by simplicity.

<br/>
![Figure 002][Figure002]{:class="center-img"}
<br/>

The above Figure represents a "Mask Generator".  
The input pixels values are in the upper section. They get processed by this "black box", which operates based on a function $ f \left( x \right) $ and the pixel output that is generated is found in the bottom section.
At the output, everything is black (Low values) with the exception of pixels around 128 that are mapped to white (High values) which suggests that a "Midtones Mask" has been generated.  

Simple fact, images are discrete in their values.
For instance, in the case of an 8 Bit image, the discrete values are in the range {0, 1, ..., 254, 255}, occupying 256 (Which equals $ {2}^{8} $) available slots.  
So a *Mask Generator* (Luminosity Mask Generator) has to designate an output value for each value in the input discrete _domain_.

If the output image is also, let's say 8 Bit, then the output values are also within the range {0, 1, ..., 254, 255} which means one need to map 256 values into 256 values.  
In the Computer Science world this process is done using a [Look Up Table](https://en.wikipedia.org/wiki/Lookup_table) (LUT).  

Over time some masks got their own naming according to the properties of the values assigned:  
 *  If it designates high output values to low input values, and low values to the rest it is called *Shadows Mask Generator*.  
    The output mask is called *Shadows Luminosity Mask* which reveals shadows and blocks everything else.
 *  If it designates high output values to mid input values, and low values to the rest it is called *Midtones Mask Generator*.  
    The output mask is called *Midtones Luminosity Mask* which reveals midtones and blocks everything else.
 *  If it designates high output values to high input values, and low values to the rest it is called *Highlights Mask Generator*.
    The output mask is called *Highlights Luminosity Mask* which reveals highlights and blocks everything elee.

This is the mask generation transformation (Mapping), and basically this is all theory there is to know.

### Examples
Let's do some stretching by simple example of 2 main building blocks of the Luminosity Mask world.  
We will assume an 8 Bit Image, hence input and output pixel values are given by {0, 1, 2, ..., 254, 255}.

The most basic Mapping / LUT / Function (All are different names to the same idea) is the _identity_ mapping:

$$ f \left( x \right) = x $$

Namely, the output value – that is $ f \left( x \right) $ - is identical to the input value, which is $ x $.  
This mask is called "Highlights Luminosity Mask". Why? Because low input values (Shadows) are mapped to low output values (Dark pixels), and high input values (Highlights) are mapped to high output values (Light pixes).  
The result is a mask where shadows are dark (Not selected) and highlights are light (Selected) – hence the name.

Another basic mask is given by the negative (Inverse) of the Highlights Mask which is the "Shadows Luminosity Mask":

$$ f \left( x \right) = 255 - x $$

Here, to low input values (Shadows) correspond high output values, and to high input values correspond high output values (light pixes). The result is a mask where shadows are light (selected) and highlights are dark (not selected).

Using those 2 building blocks one could generate many other masks targeting different Tonal Ranges (Something we will get to later).

<br/>
![Figure 003][Figure003]{:class="center-img"}
<br/>

As can be seen above, the Midtones Mask is generated by scaled multiplication of the Highlights Mask and the Shadows Mask.
This is one way to achieve this, not necessarily what's used usually (We'll talk about that).  
Moreover, as can be seen by the Harmonic Function, one could do any mapping one wish.

**Remark**
In practice, data is scaled into [0, 1] range as operations, such as multiplication, makes more sense in that domain. So the range {0, 1, 2, ..., 254, 255} becomes {0 / 255, 1 / 255, 2 /255, ..., 254 / 255, 255 / 255}. This is exactly what's done in the above figure.

## In Practice
So now we know what a Luminosity Mask Generator is, and what it is doing.
On the next step, let's try to understand how this is done in Photoshop in most cases.  
As discussed above, one need to create a LUT and there are two main approaches doing so - The Calculations Tool or the Curve Tool.
One can apply each of those on Grayscale Image and the output is basically Luminosity Mask.

### Curve Tool
The [Curve Tool](https://helpx.adobe.com/photoshop/using/curves-adjustment.html) is a LUT table visualized by a Curve.  
It practically lets the user draw the LUT using a flexible "Curve".

<br/>
![Figure 004][Figure004]{:class="center-img"}
<br/>

On the figure above, one could see Photoshop's Curve tool.
On its bottom, horizontally, you can see the input values. On the left, vertically, you can see the output value. You match between each value just by altering the curve according to your wish.  
Basically school days function, that's what it is, drawing a function.  

**Luminosity Mask Recipe by Curve Tool**
 1. Create a Grayscale version of the image in a new layer ([Extract Luminosity](https://photoshoptrainingchannel.com/tips/loading-luminosity-keyboard-shortcut/), Select one of the channels, Desaturate the image, Use Channel Mixer, etc...).
 2. Open the Curve Tool.
 3. Draw the desired LUT.
 4. Use the result as a mask -> Luminosity Mask.

### Calculations Tool
Using [Calculations Tool](https://helpx.adobe.com/photoshop/using/channel-calculations.html) one could apply simple Math operations on Layer / Channel / etc...  
Namely we can combine Math operations (Add, Subtract, Multiply and even more esoteric functions) by repetitive use of the Calculation tool.  
For instance, using the Calculation Tool we could easily generate the Midtones Mask from above by multiplying a layer and its inverse and scaling result by 4.
So it gives us the option to use `+`, `-`, `*`, `/` on images, but not much more than that.

### Comparison - Curve Tool vs. Calculations Tool
Let's summarize the differences between those 2 approaches:
<br/>
<br/>

{:.table .table-bordered}
|               | Curve Tool                                                                                                                                                                       | Calculation Tool                                                                                                                                              |
|---------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------|
| Advantages    | - No limits what so ever on the shape of the selection. <br/> - Complex mask can be achieved in operation. <br/>                                                                       | - Can be translated into exact Mathematical expression. <br/> - Smooth result and gets better as the mode (8, 16, 32 Bit) get higher. <br/>                         |
| Disadvantages | - Photoshop Curves are quantized into 256 levels which makes them less smooth. <br/> - No parameterization (Unless scripted) hence hard to be accurate and consistent. <br/> | - Limited to what can be done using the Blend Mode operations on the base Grayscale image. <br/> - Requires repetitive operations to get special selections (Slow). <br/> |
| Remarks       | Used by many Luminosity Mask panels out there, yet unless result can be achieved using Calculations, quality wise it is better use Calculations. <br/>                                 | Usually used for its quality yet limited either by speed or can get arbitrary selection. <br/>                                                                     |

<br/>
<br/>

# Building Masks
In this part we will show how most of the masks out there are built using the Lego bricks we created.

<br/>
![Figure 005][Figure005]{:class="center-img"}
<br/>

As one could see above, using Addition, Subtraction and Multiplication (Intersection), all operations available on Layers / Channels / Masks in Photoshop, one could easily generate all those "Classic" Luminosity Masks one could find in the wild (Wild world of the Luminosity Masks Panels).

Those with "Sharp Eye" would pay attention to something strange - Midtones Mask 001 is all black, see the function below:

$$ f \left( x \right) = 1 - x - ( 1 - x ) = 1 - 1 + x - x = 0 $$

Yet in practice, in all products out there... It is not?! So what's going on?  
Clearly they all state that the Midtones Mask is created by subtracting the Highlights and Shadows masks from the all white mask.  
So it is, by all means, should be all black mask while it is not.

Well, what you see above is ideal Masks, while Photoshop can not generate them in this quality.  
The current methods to create them usually use Photoshop's steps which aren't doing this exact Math.  
We'll talk more on those pitfalls and strange behaviour of classic Luminosity Masks (And their generation) in the next writing.

# Summary
Now we understood what Luminosity Masks really are, the actual operations and Math behind them.  
We have shown the Luminosity Mask generation is no more than the most simple operation on grayscale image - Apply LUT / Pixel Wise Mapping.
What we saw is that there 2 main approaches for Luminosity Masks in Photoshop. While one gives the most flexibility and efficiency (Curve Tool) it lacks with quality and the other which generates smooth masks (Calculation Tool) has speed issues when trying to generate complex masks and some miss calculations in the process.
How can we solve those?

Well, this is what [Fixel Zone Selector][2] is all about.  
In the [next part](https://fixelalgorithms.co/news/2018/11/luminosity-mask-002/index.html) we'll talk about [Zone Selector][2] approach to Luminosity Masks.

## Image Credit
 *  [Lighthouse Image](https://www.flickr.com/photos/magnetismus/8399258607/) - Credit to [magnetismus](https://www.flickr.com/people/magnetismus/).
 *  [Schwaigsee Lake](https://www.freeimages.com/photo/schwaigsee-lake-1342788) - Credit to [Alfred Borchard](https://www.freeimages.com/photographer/Alfi007-51075).
 *  [Simple Living](https://www.flickr.com/photos/lightsamples/22552453147) - Credit to [Malcolm Carlaw](https://www.flickr.com/photos/lightsamples/).


## Resources
 *  [Luminosity Mask: The Complete Kickstarter’s Guide](http://fotographee.com/tutorial-image-editing-luminosity-masks/).
 *  [Video - How to Generate the Classic Luminosity Masks Using Mask / Channel Operations (Add, Subtract, Intersect [Multiply])](https://www.youtube.com/watch?v=xvjno4d8uJ8).
 *  [Video - How to Generate the Classic Luminosity Masks Using Calculations (16 Bit Mode)](https://www.youtube.com/watch?v=43JbFIOckrM).
 *  [Video - Selecting Using Luminosity Masks (Using Curve Tool)](https://www.youtube.com/watch?v=la-zWPwjuQw).
 *  [Luminosity Mask Done Right!](https://fixelalgorithms.co/news/2018/11/luminosity-mask-002/index.html) - The 2nd part of this blog post.


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
  [Figure001]: {{site.baseurl}}/news/images/LuminosityMask001/GrayScaleImageGeneration.png "Figure 001 - Extracting Luminosity Channel from RGB Image"
  [Figure002]: {{site.baseurl}}/news/images/LuminosityMask001/MaskGenerator.png "Figure 002 - Mapping Grayscale Image into Luminosity Mask"
  [Figure003]: {{site.baseurl}}/news/images/LuminosityMask001/LuminosityMaskShowCaseAnimated.png "Figure 003 - Luminosity Mask Generation"
  [Figure004]: {{site.baseurl}}/news/images/LuminosityMask001/PhotoshopCurveTool.png "Figure 004 - Photoshop Curve Tool"
  [Figure005]: {{site.baseurl}}/news/images/LuminosityMask001/LuminosityMaskRecipesAnimated.png "Figure 005 - Luminosity Masks Recipes"
